<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\UserBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class DashboardController extends Controller
{
    /**
     * Main dashboard (redirects based on role)
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isAuthor()) {
            return $this->authorDashboard();
        }

        return $this->readerDashboard();
    }

    /**
     * Reader dashboard
     */
    private function readerDashboard()
    {
        $user = Auth::user();

        $subscription = $user->activeSubscription;

        $currentlyReading = UserBook::where('user_id', $user->id)
            ->where('status', 'reading')
            ->with('book')
            ->orderByDesc('last_read_at')
            ->take(4)
            ->get();

        $completedBooks = UserBook::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        $recommendedBooks = Book::published()
            ->whereNotIn('id', $user->userBooks->pluck('book_id'))
            ->with(['author', 'category'])
            ->inRandomOrder()
            ->take(6)
            ->get();

        $stats = [
            'books_read' => $user->books_read,
            'reading_time' => $user->reading_time_minutes,
            'books_in_progress' => $currentlyReading->count(),
        ];

        return view('dashboard.reader', compact(
            'user',
            'subscription',
            'currentlyReading',
            'completedBooks',
            'recommendedBooks',
            'stats'
        ));
    }

    /**
     * Author dashboard
     */
    public function authorDashboard()
    {
        $user = Auth::user();

        $wallet = $user->getOrCreateWallet();

        $myBooks = $user->books()
            ->with('category')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        $pendingBooks = $user->books()->where('status', 'pending')->count();
        $publishedBooks = $user->books()->where('status', 'approved')->count();
        $totalReads = $user->books()->sum('reads_count');
        $totalViews = $user->books()->sum('views_count');

        // Monthly earnings chart data
        $monthlyEarnings = $wallet->transactions()
            ->earnings()
            ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(amount) as total')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get();

        $recentTransactions = $wallet->transactions()
            ->with('book')
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        return view('dashboard.author', compact(
            'user',
            'wallet',
            'myBooks',
            'pendingBooks',
            'publishedBooks',
            'totalReads',
            'totalViews',
            'monthlyEarnings',
            'recentTransactions'
        ));
    }

    /**
     * Author statistics
     */
    public function authorStatistics()
    {
        $user = Auth::user();

        $books = $user->publishedBooks()
            ->withCount(['userBooks', 'reviews'])
            ->orderByDesc('reads_count')
            ->get();

        $totalReads = $books->sum('reads_count');
        $totalViews = $books->sum('views_count');
        $averageRating = $books->avg('average_rating');

        // Reading stats by book
        $readingStats = $books->map(function ($book) use ($totalReads) {
            return [
                'book' => $book,
                'percentage' => $totalReads > 0 ? ($book->reads_count / $totalReads) * 100 : 0,
            ];
        });

        return view('dashboard.author-statistics', compact(
            'user',
            'books',
            'totalReads',
            'totalViews',
            'averageRating',
            'readingStats'
        ));
    }

    /**
     * Profile page
     */
    public function profile()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    /**
     * Update profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ]);

        $data = $request->only(['name', 'email', 'phone', 'bio']);

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        return back()->with('success', __('messages.profile_updated'));
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', __('messages.password_updated'));
    }

    /**
     * Delete account
     */
    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = Auth::user();

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', __('messages.account_deleted'));
    }

    /**
     * Notifications
     */
    public function notifications()
    {
        $notifications = Auth::user()->notifications()->paginate(20);
        return view('notifications', compact('notifications'));
    }

    /**
     * Mark notification as read
     */
    public function markNotificationRead($id)
    {
        Auth::user()->notifications()->where('id', $id)->update(['read_at' => now()]);
        return back();
    }

    /**
     * Mark all notifications as read
     */
    public function markAllNotificationsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back()->with('success', __('messages.notifications_marked_read'));
    }
}
