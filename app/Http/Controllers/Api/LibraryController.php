<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\UserBook;
use Illuminate\Http\Request;

class LibraryController extends Controller
{
    public function index(Request $request)
    {
        $userBooks = UserBook::where('user_id', $request->user()->id)
            ->with(['book.author:id,name', 'book.category:id,name'])
            ->orderByDesc('last_read_at')
            ->paginate(20);

        return response()->json($userBooks);
    }

    public function addBook(Request $request, Book $book)
    {
        $user = $request->user();

        if (!$user->hasActiveSubscription()) {
            return response()->json(['message' => __('messages.subscription_required')], 403);
        }

        $subscription = $user->activeSubscription;

        if (!$subscription->hasRemainingBooks()) {
            return response()->json(['message' => __('messages.book_limit_reached')], 403);
        }

        $userBook = UserBook::firstOrCreate(
            ['user_id' => $user->id, 'book_id' => $book->id],
            [
                'subscription_id' => $subscription->id,
                'total_pages' => $book->pages_count,
                'status' => 'reading',
            ]
        );

        if ($userBook->wasRecentlyCreated) {
            $subscription->decrementBooks();
            $subscription->addSelectedBook($book->id);
        }

        return response()->json($userBook->load('book'), 201);
    }

    public function removeBook(Request $request, Book $book)
    {
        UserBook::where('user_id', $request->user()->id)
            ->where('book_id', $book->id)
            ->delete();

        return response()->json(['message' => 'Book removed from library']);
    }

    public function history(Request $request)
    {
        $history = UserBook::where('user_id', $request->user()->id)
            ->with(['book.author:id,name'])
            ->whereNotNull('last_read_at')
            ->orderByDesc('last_read_at')
            ->paginate(50);

        return response()->json($history);
    }

    /**
     * Get accessible books based on subscription status
     * Active subscription: all books
     * Expired subscription: 50% of books (retained)
     */
    public function getAccessibleBooks(Request $request)
    {
        $user = $request->user();
        
        // Get all user books
        $userBooks = UserBook::where('user_id', $user->id)
            ->with(['book.author:id,name', 'book.category:id,name'])
            ->orderBy('created_at', 'asc')
            ->get();

        // If user has active subscription, return all books
        if ($user->hasActiveSubscription()) {
            return response()->json([
                'books' => $userBooks,
                'has_active_subscription' => true,
                'accessible_count' => $userBooks->count(),
                'total_count' => $userBooks->count(),
            ]);
        }

        // If subscription expired, return only 50% (retained books)
        $totalBooks = $userBooks->count();
        $retainedCount = ceil($totalBooks / 2); // 50% rounded up
        $accessibleBooks = $userBooks->take($retainedCount);

        return response()->json([
            'books' => $accessibleBooks->values(),
            'has_active_subscription' => false,
            'accessible_count' => $retainedCount,
            'total_count' => $totalBooks,
            'message' => 'Votre abonnement a expiré. Vous avez accès à ' . $retainedCount . ' livres sur ' . $totalBooks . '.',
        ]);
    }
}
