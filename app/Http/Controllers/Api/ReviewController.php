<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index(Book $book)
    {
        $reviews = $book->approvedReviews()
            ->with('user')
            ->orderByDesc('created_at')
            ->paginate(10);

        return response()->json($reviews);
    }

    public function store(Request $request, Book $book)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'content' => 'required|string|max:2000',
        ]);

        // Check if already reviewed
        $existing = BookReview::where('user_id', Auth::id())->where('book_id', $book->id)->exists();
        if ($existing) {
             return response()->json(['message' => 'already_reviewed'], 409);
        }

        $userBook = Auth::user()->userBooks()->where('book_id', $book->id)->first();

        $review = BookReview::create([
            'user_id' => Auth::id(),
            'book_id' => $book->id,
            'rating' => $validated['rating'],
            'title' => $validated['title'],
            'content' => $validated['content'],
            'is_verified_purchase' => $userBook !== null,
            'status' => 'approved', // Auto-approve for now or pending based on settings
        ]);
        
        $book->updateRating();

        return response()->json($review, 201);
    }

    public function destroy(\App\Models\BookReview $review)
    {
        if ($review->user_id !== Auth::id()) {
            return response()->json(['message' => 'unauthorized'], 403);
        }

        $book = $review->book;
        $review->delete();
        $book->updateRating();

        return response()->json(['message' => 'deleted']);
    }
}
