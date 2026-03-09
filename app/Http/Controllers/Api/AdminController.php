<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookReport;
use App\Models\Category;
use App\Models\NewsletterSubscriber;
use App\Models\Setting;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Models\WithdrawalRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Admin Dashboard
     */
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_books' => Book::count(),
            'pending_books' => Book::where('status', 'pending')->count(),
            'active_subscriptions' => Subscription::where('status', 'active')->count(),
            'total_revenue' => Subscription::where('status', 'active')->sum('amount_paid'),
            'pending_withdrawals' => WithdrawalRequest::where('status', 'pending')->count(),
            'pending_reports' => BookReport::where('status', 'pending')->count(),
        ];

        $recentUsers = User::orderByDesc('created_at')->take(5)->get();
        $recentBooks = Book::with('author')->orderByDesc('created_at')->take(5)->get();

        return response()->json([
            'stats' => $stats,
            'recent_users' => $recentUsers,
            'recent_books' => $recentBooks,
        ]);
    }

    /**
     * Users Management
     */
    public function users(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->q . '%')
                  ->orWhere('email', 'like', '%' . $request->q . '%');
            });
        }

        $users = $query->orderByDesc('created_at')->paginate(20);

        return response()->json($users);
    }

    public function showUser($id)
    {
        $user = User::with(['books', 'subscriptions', 'wallet'])->findOrFail($id);
        return response()->json($user);
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'role' => 'sometimes|in:reader,author,admin',
        ]);

        $user->update($validated);

        return response()->json([
            'message' => 'Utilisateur mis à jour',
            'user' => $user,
        ]);
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'Utilisateur supprimé']);
    }

    public function toggleUserStatus($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = !$user->is_active;
        $user->save();

        return response()->json([
            'message' => 'Statut modifié',
            'user' => $user,
        ]);
    }

    /**
     * Books Management
     */
    public function books(Request $request)
    {
        $query = Book::with(['author', 'category']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->q . '%');
        }

        $books = $query->orderByDesc('created_at')->paginate(20);

        return response()->json($books);
    }

    public function pendingBooks()
    {
        $books = Book::with(['author', 'category'])
            ->where('status', 'pending')
            ->orderByDesc('created_at')
            ->paginate(20);

        return response()->json($books);
    }

    public function previewBook($id)
    {
        $book = Book::with(['author', 'category'])->findOrFail($id);
        return response()->json($book);
    }

    public function approveBook($id)
    {
        $book = Book::findOrFail($id);
        $book->status = 'approved';
        $book->approved_at = now();
        $book->save();

        return response()->json([
            'message' => 'Livre approuvé',
            'book' => $book,
        ]);
    }

    public function rejectBook(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string']);
        
        $book = Book::findOrFail($id);
        $book->status = 'rejected';
        $book->rejection_reason = $request->reason;
        $book->save();

        return response()->json([
            'message' => 'Livre rejeté',
            'book' => $book,
        ]);
    }

    public function setWelcomeBook($id)
    {
        // Remove previous welcome book
        Book::where('is_welcome_book', true)->update(['is_welcome_book' => false]);
        
        $book = Book::findOrFail($id);
        $book->is_welcome_book = true;
        $book->save();

        return response()->json([
            'message' => 'Livre de bienvenue défini',
            'book' => $book,
        ]);
    }

    /**
     * Reports Management
     */
    public function reports(Request $request)
    {
        $query = BookReport::with(['book', 'user']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reports = $query->orderByDesc('created_at')->paginate(20);

        return response()->json($reports);
    }

    public function showReport($id)
    {
        $report = BookReport::with(['book', 'user'])->findOrFail($id);
        return response()->json($report);
    }

    public function handleReport(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:resolved,dismissed',
            'admin_notes' => 'nullable|string',
        ]);

        $report = BookReport::findOrFail($id);
        $report->status = $request->status;
        $report->admin_notes = $request->admin_notes;
        $report->resolved_at = now();
        $report->save();

        return response()->json([
            'message' => 'Signalement traité',
            'report' => $report,
        ]);
    }

    /**
     * Subscriptions
     */
    public function subscriptions(Request $request)
    {
        $query = Subscription::with(['user', 'plan']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $subscriptions = $query->orderByDesc('created_at')->paginate(20);

        return response()->json($subscriptions);
    }

    /**
     * Plans Management
     */
    public function plans()
    {
        $plans = SubscriptionPlan::orderBy('price')->get();
        return response()->json($plans);
    }

    public function storePlan(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:subscription_plans',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'books_limit' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $plan = SubscriptionPlan::create($validated);

        return response()->json([
            'message' => 'Plan créé',
            'plan' => $plan,
        ], 201);
    }

    public function updatePlan(Request $request, $id)
    {
        $plan = SubscriptionPlan::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'price' => 'sometimes|numeric|min:0',
            'duration_days' => 'sometimes|integer|min:1',
            'books_limit' => 'sometimes|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $plan->update($validated);

        return response()->json([
            'message' => 'Plan mis à jour',
            'plan' => $plan,
        ]);
    }

    /**
     * Withdrawals Management
     */
    public function withdrawals(Request $request)
    {
        $query = WithdrawalRequest::with(['user']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $withdrawals = $query->orderByDesc('created_at')->paginate(20);

        return response()->json($withdrawals);
    }

    public function approveWithdrawal($id)
    {
        $withdrawal = WithdrawalRequest::findOrFail($id);
        
        if ($withdrawal->status !== 'pending') {
            return response()->json(['message' => 'Retrait déjà traité'], 400);
        }

        $withdrawal->status = 'approved';
        $withdrawal->processed_at = now();
        $withdrawal->save();

        // Deduct from wallet
        $wallet = $withdrawal->user->wallet;
        $wallet->balance -= $withdrawal->amount;
        $wallet->save();

        return response()->json([
            'message' => 'Retrait approuvé',
            'withdrawal' => $withdrawal,
        ]);
    }

    public function rejectWithdrawal(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string']);
        
        $withdrawal = WithdrawalRequest::findOrFail($id);
        
        if ($withdrawal->status !== 'pending') {
            return response()->json(['message' => 'Retrait déjà traité'], 400);
        }

        $withdrawal->status = 'rejected';
        $withdrawal->rejection_reason = $request->reason;
        $withdrawal->processed_at = now();
        $withdrawal->save();

        return response()->json([
            'message' => 'Retrait rejeté',
            'withdrawal' => $withdrawal,
        ]);
    }

    /**
     * Settings
     */
    public function settings()
    {
        $settings = Setting::pluck('value', 'key');
        return response()->json($settings);
    }

    public function updateSettings(Request $request)
    {
        foreach ($request->all() as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return response()->json(['message' => 'Paramètres mis à jour']);
    }

    /**
     * Newsletter
     */
    public function sendNewsletter(Request $request)
    {
        $request->validate([
            'subject' => 'required|string',
            'content' => 'required|string',
        ]);

        $subscribers = NewsletterSubscriber::where('is_active', true)->get();

        // Send newsletter logic here
        
        return response()->json([
            'message' => 'Newsletter envoyée à ' . $subscribers->count() . ' abonnés',
        ]);
    }

    /**
     * Statistics
     */
    public function statistics()
    {
        $stats = [
            'users_by_role' => User::select('role', DB::raw('count(*) as count'))
                ->groupBy('role')
                ->get(),
            'books_by_status' => Book::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get(),
            'subscriptions_by_status' => Subscription::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get(),
            'revenue_by_month' => Subscription::select(
                    DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                    DB::raw('SUM(amount_paid) as revenue')
                )
                ->where('status', 'active')
                ->groupBy('month')
                ->orderBy('month', 'desc')
                ->take(12)
                ->get(),
        ];

        return response()->json($stats);
    }

    public function financialReports()
    {
        $reports = [
            'total_revenue' => Subscription::where('status', 'active')->sum('amount_paid'),
            'pending_withdrawals' => WithdrawalRequest::where('status', 'pending')->sum('amount'),
            'approved_withdrawals' => WithdrawalRequest::where('status', 'approved')->sum('amount'),
            'transactions' => WalletTransaction::with('user')
                ->orderByDesc('created_at')
                ->take(50)
                ->get(),
        ];

        return response()->json($reports);
    }
}
