<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookReport;
use App\Models\NewsletterSubscriber;
use App\Models\Setting;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\WithdrawalRequest;
use App\Services\EmailService;
use App\Services\NokashService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_books' => Book::count(),
            'pending_books' => Book::pending()->count(),
            'active_subscriptions' => Subscription::active()->count(),
            'total_revenue' => Subscription::where('status', 'active')->sum('amount_paid'),
            'pending_withdrawals' => WithdrawalRequest::where('status', 'pending')->count(),
            'pending_reports' => BookReport::where('status', 'pending')->count(),
        ];

        $recentUsers = User::orderByDesc('created_at')->take(5)->get();
        $recentBooks = Book::with('author')->orderByDesc('created_at')->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentBooks'));
    }

    // Users
    public function users(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(fn($q) => $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
        }

        $users = $query->orderByDesc('created_at')->paginate(20)->withQueryString();
        return view('admin.users.index', compact('users'));
    }

    public function showUser(User $user)
    {
        $user->load(['subscriptions', 'books', 'wallet']);
        return view('admin.users.show', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:reader,author,editor,admin',
            'is_active' => 'boolean',
        ]);

        $user->update($validated);
        return back()->with('success', __('messages.user_updated'));
    }

    public function deleteUser(User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', __('messages.cannot_delete_admin'));
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', __('messages.user_deleted'));
    }

    public function toggleUserStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        return back()->with('success', __('messages.user_status_toggled'));
    }

    // Books
    public function books(Request $request)
    {
        $query = Book::with(['author', 'category']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $books = $query->orderByDesc('created_at')->paginate(20)->withQueryString();
        return view('admin.books.index', compact('books'));
    }

    public function pendingBooks()
    {
        $books = Book::pending()
            ->with(['author', 'category'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.books.pending', compact('books'));
    }

    public function previewBook(Book $book)
    {
        $book->load(['author', 'category']);
        return view('admin.books.preview', compact('book'));
    }

    public function approveBook(Book $book)
    {
        $book->update([
            'status' => 'approved',
            'published_at' => now(),
        ]);

        try {
            $emailService = new EmailService();
            $subscribers = NewsletterSubscriber::active()->whereNotNull('user_id')->get();
            foreach ($subscribers as $subscriber) {
                if ($subscriber->user) {
                    $emailService->sendNewBookNotification($book, $subscriber->user);
                }
            }
        } catch (\Exception $e) {
            Log::error('Book approval email error: ' . $e->getMessage());
        }

        return back()->with('success', __('messages.book_approved'));
    }

    public function rejectBook(Request $request, Book $book)
    {
        $request->validate(['rejection_reason' => 'required|string|max:1000']);

        $book->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('success', __('messages.book_rejected'));
    }

    public function setWelcomeBook(Book $book)
    {
        // Remove previous welcome book flag
        Book::where('is_free_welcome_book', true)->update(['is_free_welcome_book' => false]);

        $book->update(['is_free_welcome_book' => true]);
        Setting::setValue('welcome_book_id', $book->id, 'integer', 'books');

        return back()->with('success', __('messages.welcome_book_set'));
    }

    // Reports
    public function reports()
    {
        $reports = BookReport::with(['user', 'book.author'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.reports.index', compact('reports'));
    }

    public function showReport(BookReport $report)
    {
        $report->load(['user', 'book.author']);
        return view('admin.reports.show', compact('report'));
    }

    public function handleReport(Request $request, BookReport $report)
    {
        $validated = $request->validate([
            'action' => 'required|in:dismiss,warn_author,remove_book',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $report->update([
            'status' => 'reviewed',
            'admin_action' => $validated['action'],
            'admin_notes' => $validated['admin_notes'],
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
        ]);

        if ($validated['action'] === 'remove_book') {
            $report->book->update(['status' => 'rejected']);
        }

        return redirect()->route('admin.reports.index')->with('success', __('messages.report_handled'));
    }

    // Subscriptions
    public function subscriptions()
    {
        $subscriptions = Subscription::with('user')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    // Plans
    // Plans
    public function plans()
    {
        $plans = SubscriptionPlan::orderByDesc('created_at')->get();
        return view('admin.plans.index', compact('plans'));
    }

    public function createPlan()
    {
        return view('admin.plans.create');
    }

    public function storePlan(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:reader,author',
            'duration' => 'required|in:monthly,quarterly,yearly',
            'duration_days' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'books_limit' => 'nullable|integer|min:0',
            'publications_limit' => 'nullable|integer|min:0',
            'platform_commission_rate' => 'required|numeric|min:0|max:1',
            'features' => 'nullable|string',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
        ]);

        if (isset($validated['features'])) {
            $validated['features'] = array_map('trim', explode("\n", $validated['features']));
        }

        $validated['slug'] = Str::slug($validated['name'] . '-' . $validated['type'] . '-' . $validated['duration']);

        SubscriptionPlan::create($validated);

        return redirect()->route('admin.plans.index')->with('success', __('messages.plan_created'));
    }

    public function editPlan($id)
    {
        $plan = SubscriptionPlan::findOrFail($id);
        return view('admin.plans.edit', compact('plan'));
    }

    public function updatePlan(Request $request, $id)
    {
        $plan = SubscriptionPlan::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'books_limit' => 'nullable|integer|min:0',
            'publications_limit' => 'nullable|integer|min:0',
            'platform_commission_rate' => 'required|numeric|min:0|max:1',
            'features' => 'nullable|string',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
        ]);

        if (isset($validated['features'])) {
            $validated['features'] = array_map('trim', explode("\n", $validated['features']));
        }

        $plan->update($validated);

        return redirect()->route('admin.plans.index')->with('success', __('messages.plan_updated'));
    }

    public function deletePlan($id)
    {
        $plan = SubscriptionPlan::findOrFail($id);
        $plan->delete();

        return redirect()->route('admin.plans.index')->with('success', 'Plan supprimé avec succès.');
    }

    // Withdrawals
    public function withdrawals()
    {
        $withdrawals = WithdrawalRequest::with(['user'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.withdrawals.index', compact('withdrawals'));
    }

    public function approveWithdrawal(WithdrawalRequest $withdrawal)
    {
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', __('messages.withdrawal_already_processed'));
        }

        try {
            $wallet = $withdrawal->user->getOrCreateWallet();

            // Process payout via Nokash
            $nokash = new NokashService();
            $method = $withdrawal->payment_method === 'mtn' ? 'MTN_MOMO' : 'ORANGE_MONEY';

            $result = $nokash->payout(
                $withdrawal->amount,
                $withdrawal->phone,
                $method,
                route('payment.nokash.callback')
            );

            // Debit wallet
            $wallet->debit($withdrawal->amount, 'Retrait approuvé - ' . $withdrawal->payment_method);
            $wallet->decrement('pending_amount', $withdrawal->amount);

            $withdrawal->update([
                'status' => 'approved',
                'payment_reference' => $result['data']['reference'] ?? null,
                'processed_at' => now(),
            ]);

            try {
                $emailService = new EmailService();
                $emailService->sendWithdrawalConfirmation(
                    $withdrawal->user,
                    $withdrawal->amount,
                    $withdrawal->payment_method
                );
            } catch (\Exception $e) {
                Log::error('Withdrawal email error: ' . $e->getMessage());
            }

            return back()->with('success', __('messages.withdrawal_approved'));
        } catch (\Exception $e) {
            Log::error('Withdrawal payout error: ' . $e->getMessage());
            return back()->with('error', __('messages.withdrawal_payout_error'));
        }
    }

    public function rejectWithdrawal(Request $request, WithdrawalRequest $withdrawal)
    {
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', __('messages.withdrawal_already_processed'));
        }

        $wallet = $withdrawal->user->getOrCreateWallet();
        $wallet->decrement('pending_amount', $withdrawal->amount);

        $withdrawal->update([
            'status' => 'rejected',
            'admin_notes' => $request->input('reason'),
            'processed_at' => now(),
        ]);

        return back()->with('success', __('messages.withdrawal_rejected'));
    }

    // Settings
    public function settings()
    {
        $settings = Setting::all()->groupBy('group');
        return view('admin.settings.index', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        foreach ($request->except('_token', '_method') as $key => $value) {
            $setting = Setting::where('key', $key)->first();
            if ($setting) {
                $setting->update(['value' => $value]);
                \Illuminate\Support\Facades\Cache::forget('setting_' . $key);
            }
        }

        return back()->with('success', __('messages.settings_updated'));
    }

    // Newsletter
    public function newsletter()
    {
        $subscribers = NewsletterSubscriber::active()->paginate(20);
        $totalSubscribers = NewsletterSubscriber::active()->count();

        return view('admin.newsletter.index', compact('subscribers', 'totalSubscribers'));
    }

    public function sendNewsletter(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $subscribers = NewsletterSubscriber::active()->get()->toArray();

        $emailService = new EmailService();
        $sent = $emailService->sendNewsletter($validated['subject'], $validated['content'], $subscribers);

        return back()->with('success', __('messages.newsletter_sent', ['count' => $sent]));
    }

    // Statistics
    public function statistics()
    {
        $isSqlite = \Illuminate\Support\Facades\DB::getDriverName() === 'sqlite';
        $select = $isSqlite
            ? "strftime('%m', created_at) as month, strftime('%Y', created_at) as year, COUNT(*) as count, SUM(amount_paid) as revenue"
            : 'MONTH(created_at) as month, YEAR(created_at) as year, COUNT(*) as count, SUM(amount_paid) as revenue';

        $monthlySubscriptions = Subscription::where('status', 'active')
            ->selectRaw($select)
            ->groupBy('year', 'month')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->take(12)
            ->get();

        $topBooks = Book::published()
            ->orderByDesc('reads_count')
            ->take(10)
            ->get();

        $topAuthors = User::where('role', 'author')
            ->withCount('books')
            ->withSum('books', 'reads_count')
            ->orderByDesc('books_sum_reads_count')
            ->take(10)
            ->get();

        return view('admin.statistics.index', compact('monthlySubscriptions', 'topBooks', 'topAuthors'));
    }

    public function financialReports()
    {
        $totalRevenue = Subscription::where('status', 'active')->sum('amount_paid');
        $totalCommissions = Subscription::where('status', 'active')->sum('platform_commission');
        $totalWithdrawals = WithdrawalRequest::where('status', 'approved')->sum('amount');
        $pendingWithdrawals = WithdrawalRequest::where('status', 'pending')->sum('amount');

        $isSqlite = \Illuminate\Support\Facades\DB::getDriverName() === 'sqlite';
        $select = $isSqlite
            ? "strftime('%m', created_at) as month, strftime('%Y', created_at) as year, SUM(amount_paid) as revenue, SUM(platform_commission) as commission"
            : 'MONTH(created_at) as month, YEAR(created_at) as year, SUM(amount_paid) as revenue, SUM(platform_commission) as commission';

        $monthlyData = Subscription::where('status', 'active')
            ->selectRaw($select)
            ->groupBy('year', 'month')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->take(12)
            ->get();

        return view('admin.statistics.financial', compact(
            'totalRevenue', 'totalCommissions', 'totalWithdrawals', 'pendingWithdrawals', 'monthlyData'
        ));
    }
}
