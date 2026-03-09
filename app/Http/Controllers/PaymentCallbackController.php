<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Setting;
use App\Models\Subscription;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Services\EmailService;
use App\Services\NokashService;
use App\Services\PaymooneyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentCallbackController extends Controller
{
    public function nokashCallback(Request $request)
    {
        Log::info('Nokash callback received', $request->all());

        $reference = $request->input('reference');
        $status = $request->input('status');

        if ($status === 'SUCCESSFUL' && $reference) {
            $subscription = Subscription::where('payment_reference', 'like', "nokash%")
                ->where('status', 'pending')
                ->latest()
                ->first();

            if ($subscription) {
                $this->activateSubscription($subscription, $reference);
            }
        }

        return response()->json(['status' => 'ok']);
    }

    public function nokashSuccess(Request $request, Subscription $subscription)
    {
        $request->validate([
            'reference' => 'required|string',
        ]);

        try {
            $nokash = new NokashService();
            $result = $nokash->verifyPayment($request->reference);

            if (isset($result['status']) && $result['status'] === 'SUCCESSFUL') {
                $this->activateSubscription($subscription, $request->reference);
                return redirect()->route('dashboard')
                    ->with('success', __('messages.subscription_activated'));
            }

            return redirect()->route('subscription.payment', $subscription)
                ->with('error', __('messages.payment_not_confirmed'));
        } catch (\Exception $e) {
            Log::error('Nokash verification error: ' . $e->getMessage());
            return redirect()->route('subscription.payment', $subscription)
                ->with('error', __('messages.payment_error'));
        }
    }

    public function paymooneyCallback(Request $request)
    {
        Log::info('PayMooney callback received', $request->all());

        $itemRef = $request->input('item_ref');
        $status = $request->input('status');

        if ($status === 'SUCCESSFUL' && $itemRef) {
            $paymooney = new PaymooneyService();
            $sessionData = $paymooney->getOrderSession($itemRef);

            if ($sessionData && isset($sessionData['subscription_id'])) {
                $subscription = Subscription::find($sessionData['subscription_id']);

                if ($subscription && $subscription->status === 'pending') {
                    $this->activateSubscription($subscription, $itemRef);
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }

    public function paymooneySuccess(Request $request)
    {
        $itemRef = $request->input('item_ref');

        if ($itemRef) {
            $paymooney = new PaymooneyService();
            $sessionData = $paymooney->getOrderSession($itemRef);

            if ($sessionData && isset($sessionData['subscription_id'])) {
                $subscription = Subscription::find($sessionData['subscription_id']);

                if ($subscription && $subscription->status === 'pending') {
                    $this->activateSubscription($subscription, $itemRef);
                }

                return redirect()->route('dashboard')
                    ->with('success', __('messages.subscription_activated'));
            }
        }

        return redirect()->route('dashboard')
            ->with('success', __('messages.payment_processing'));
    }

    public function paymooneyFail(Request $request)
    {
        return redirect()->route('pricing')
            ->with('error', __('messages.payment_failed'));
    }

    private function activateSubscription(Subscription $subscription, string $reference): void
    {
        DB::transaction(function () use ($subscription, $reference) {
            $plan = $subscription->plan;

            $subscription->update([
                'status' => 'active',
                'payment_reference' => $reference,
                'starts_at' => now(),
                'ends_at' => now()->addDays($plan->duration_days),
            ]);

            // Distribute author shares for reader subscriptions
            if ($plan->isForReaders()) {
                $this->distributeAuthorShares($subscription);
            }

            // Send confirmation email
            try {
                $emailService = new EmailService();
                $emailService->sendSubscriptionConfirmation($subscription);
            } catch (\Exception $e) {
                Log::error('Subscription email error: ' . $e->getMessage());
            }
        });
    }

    private function distributeAuthorShares(Subscription $subscription): void
    {
        $authorShare = Setting::getValue('author_share_per_subscription', 500);
        $selectedBookIds = $subscription->selected_book_ids ?? [];

        if (empty($selectedBookIds)) {
            // Get random published books to credit their authors
            $books = Book::published()
                ->where('is_premium', true)
                ->inRandomOrder()
                ->take(3)
                ->get();
        } else {
            $books = Book::whereIn('id', $selectedBookIds)->get();
        }

        $perBookShare = $books->count() > 0 ? $authorShare / $books->count() : 0;

        foreach ($books as $book) {
            if ($book->author_id) {
                $wallet = Wallet::firstOrCreate(
                    ['user_id' => $book->author_id],
                    ['balance' => 0, 'total_earned' => 0, 'total_withdrawn' => 0]
                );

                $wallet->credit(
                    $perBookShare,
                    'Revenu abonnement lecteur - ' . $book->title,
                    $subscription->id,
                    $book->id
                );

                try {
                    $emailService = new EmailService();
                    $emailService->sendEarningNotification($wallet->user, $perBookShare, $book);
                } catch (\Exception $e) {
                    Log::error('Author earning email error: ' . $e->getMessage());
                }
            }
        }
    }
}
