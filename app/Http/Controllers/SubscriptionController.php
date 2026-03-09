<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Services\EmailService;
use App\Services\NokashService;
use App\Services\PaymooneyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SubscriptionController extends Controller
{
    public function plans()
    {
        $allPlans = SubscriptionPlan::active()->ordered()->get();
        $readerPlans = $allPlans->where('type', 'reader');
        $authorPlans = $allPlans->where('type', 'author');

        return view('subscriptions.plans', compact('readerPlans', 'authorPlans'));
    }

    public function show($slug)
    {
        $plan = SubscriptionPlan::where('slug', $slug)->firstOrFail();
        return view('subscriptions.show', compact('plan'));
    }

    public function subscribe(Request $request, $slug)
    {
        $plan = SubscriptionPlan::where('slug', $slug)->firstOrFail();
        $user = Auth::user();

        // Check if user already has active subscription of same type
        $existing = $user->subscriptions()
            ->active()
            ->get();
            
        $hasSameType = false;
        
        // Single book purchases do not conflict with existing reader plans
        if ($plan->slug !== 'reader-single') {
            foreach ($existing as $sub) {
                if ($sub->plan && $sub->plan->type === $plan->type && $sub->plan->slug !== 'reader-single') {
                    $hasSameType = true;
                    break;
                }
            }
        }

        if ($hasSameType) {
            return back()->with('error', __('messages.already_subscribed'));
        }

        // Create pending subscription locally
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'amount_paid' => $plan->price,
            'platform_commission' => $plan->getPlatformCommission(),
            'status' => $plan->price == 0 ? 'active' : 'pending',
            'starts_at' => $plan->price == 0 ? now() : null,
            'ends_at' => $plan->price == 0 ? now()->addDays($plan->duration_days) : null,
            'books_remaining' => $plan->books_limit ?? 0,
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'requires_payment' => $plan->price > 0,
                'subscription' => $subscription,
                'plan' => $plan
            ]);
        }

        if ($plan->price == 0) {
            return redirect()->route('dashboard')->with('success', __('messages.subscription_activated'));
        }

        return redirect()->route('subscription.payment', $subscription);
    }

    public function payment(Subscription $subscription)
    {
        if ($subscription->user_id !== Auth::id()) {
            abort(403);
        }

        if ($subscription->status !== 'pending') {
            return redirect()->route('dashboard');
        }

        $plan = $subscription->plan;

        return view('subscriptions.payment', compact('subscription', 'plan'));
    }

    public function createPaymooneyLink(Request $request, Subscription $subscription)
    {
        if ($subscription->user_id !== Auth::id()) {
            abort(403);
        }

        try {
            $user = Auth::user();
            $plan = $subscription->plan;
            $paymooney = new PaymooneyService();

            $nameParts = explode(' ', $user->name, 2);
            $firstName = $nameParts[0];
            $lastName = $nameParts[1] ?? '';

            $result = $paymooney->createPaymentLink(
                orderData: [
                    'subscription_id' => $subscription->id,
                    'user_id' => $user->id,
                    'plan_id' => $plan->id,
                    'type' => 'subscription',
                ],
                amountXAF: $plan->price,
                email: $user->email,
                firstName: $firstName,
                lastName: $lastName,
                itemName: 'Abonnement OCaLi - ' . $plan->localized_name
            );

            if ($result['success']) {
                $subscription->update([
                    'payment_method' => 'paymooney',
                    'payment_reference' => $result['session_id'],
                ]);

                return redirect($result['payment_url']);
            }

            return back()->with('error', $result['error'] ?? __('messages.payment_error'));
        } catch (\Exception $e) {
            Log::error('PayMooney subscription error: ' . $e->getMessage());
            return back()->with('error', __('messages.payment_error'));
        }
    }
}
