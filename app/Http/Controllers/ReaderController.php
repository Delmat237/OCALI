<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\ReadingSession;
use App\Models\UserBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReaderController extends Controller
{
    public function library()
    {
        $user = Auth::user();

        $currentlyReading = UserBook::where('user_id', $user->id)
            ->where('status', 'reading')
            ->with('book.author')
            ->orderByDesc('last_read_at')
            ->get();

        $completed = UserBook::where('user_id', $user->id)
            ->where('status', 'completed')
            ->with('book.author')
            ->orderByDesc('completed_at')
            ->get();

        return view('library.index', compact('currentlyReading', 'completed'));
    }

    public function history()
    {
        $userBooks = UserBook::where('user_id', Auth::id())
            ->with('book.author')
            ->orderByDesc('last_read_at')
            ->paginate(20);

        return view('library.history', compact('userBooks'));
    }

    public function read(Book $book)
    {
        $user = Auth::user();

        // Check if user has access to this book
        $userBook = UserBook::where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->first();

        if (!$userBook) {
            // Check if it's a free welcome book
            if ($book->is_free_welcome_book) {
                $userBook = UserBook::create([
                    'user_id' => $user->id,
                    'book_id' => $book->id,
                    'total_pages' => $book->pages_count,
                    'status' => 'reading',
                ]);
            } else {
                // Check subscription
                if (!$user->hasActiveSubscription()) {
                    return redirect()->route('pricing')
                        ->with('warning', __('messages.subscription_required'));
                }

                $subscription = $user->activeSubscription;
                if (!$subscription->hasRemainingBooks()) {
                    return redirect()->route('books.show', $book)
                        ->with('warning', __('messages.book_limit_reached'));
                }

                // Add to library
                $userBook = UserBook::create([
                    'user_id' => $user->id,
                    'book_id' => $book->id,
                    'subscription_id' => $subscription->id,
                    'total_pages' => $book->pages_count,
                    'status' => 'reading',
                ]);

                $subscription->decrementBooks();
                $subscription->addSelectedBook($book->id);
            }
        }

        $userBook->update(['last_read_at' => now()]);
        
        $book->load(['author', 'category']);

        return view('reader.show', compact('book', 'userBook'));
    }

    public function updateProgress(Request $request, Book $book)
    {
        $validated = $request->validate([
            'current_page' => 'required|integer|min:0',
            'reading_time' => 'nullable|integer|min:0',
        ]);

        $userBook = UserBook::where('user_id', Auth::id())
            ->where('book_id', $book->id)
            ->firstOrFail();

        $startPage = $userBook->current_page ?? 0;
        $userBook->updateProgress($validated['current_page']);

        if (isset($validated['reading_time']) && $validated['reading_time'] > 0) {
            $userBook->addReadingTime($validated['reading_time']);

            ReadingSession::create([
                'user_book_id' => $userBook->id,
                'user_id' => Auth::id(),
                'book_id' => $book->id,
                'started_at' => now()->subMinutes($validated['reading_time']),
                'ended_at' => now(),
                'duration_minutes' => $validated['reading_time'],
                'pages_read' => max(0, $validated['current_page'] - $startPage),
                'start_page' => $startPage,
                'end_page' => $validated['current_page'],
            ]);
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'progress' => $userBook->fresh()->progress_percent]);
        }

        return back();
    }

    public function addBookmark(Request $request, Book $book)
    {
        $validated = $request->validate([
            'page' => 'required|integer|min:1',
            'note' => 'nullable|string|max:500',
        ]);

        $userBook = UserBook::where('user_id', Auth::id())
            ->where('book_id', $book->id)
            ->firstOrFail();

        $userBook->addBookmark($validated['page'], $validated['note'] ?? null);

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', __('messages.bookmark_added'));
    }
}
