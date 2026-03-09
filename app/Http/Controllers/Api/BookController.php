<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::published()->with(['author:id,name', 'category:id,name,slug']);

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $books = $query->orderByDesc('published_at')->paginate(20);

        return response()->json($books);
    }

    public function featured()
    {
        $books = Book::published()
            ->featured()
            ->with(['author:id,name', 'category:id,name,slug'])
            ->orderByDesc('published_at')
            ->take(10)
            ->get();

        return response()->json($books);
    }

    public function latest()
    {
        $books = Book::published()
            ->with(['author:id,name', 'category:id,name,slug'])
            ->orderByDesc('published_at')
            ->take(10)
            ->get();

        return response()->json($books);
    }

    public function download(Book $book)
    {
        $user = Auth::user();

        // Check permissions (reusing logic from web controller or simplifying)
        $canDownload = false;
        if ($book->author_id === $user->id || $user->isAdmin()) {
            $canDownload = true;
        } elseif ($user->userBooks()->where('book_id', $book->id)->exists()) {
            $canDownload = true;
        } elseif ($user->hasActiveSubscription()) {
            $canDownload = true;
        }

        if (!$canDownload) {
            return response()->json(['message' => __('messages.subscription_required')], 403);
        }

        if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($book->file_path)) {
            return response()->json(['message' => __('messages.file_not_found')], 404);
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->download($book->file_path, \Illuminate\Support\Str::slug($book->title) . '.' . $book->file_type);
    }

    public function show($id)
    {
        $book = Book::with(['author:id,name,bio,avatar', 'category', 'approvedReviews.user:id,name,avatar'])
            ->where('id', $id)
            ->orWhere('slug', $id)
            ->firstOrFail();

        if (!$book->isVerified()) { // Assuming isPublished() check
             if (!Auth::check() || (Auth::id() !== $book->author_id && !Auth::user()->isAdmin())) {
                abort(404);
             }
        }
        
        $book->incrementViews();

        return response()->json($book);
    }
}
