<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthorBookController extends Controller
{
    public function index()
    {
        $books = Book::where('author_id', Auth::id())
            ->with('category')
            ->orderByDesc('created_at')
            ->get();

        return response()->json($books);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'isbn' => 'nullable|string|max:50',
            'doi' => 'nullable|string|max:100',
            'price' => 'nullable|numeric|min:0',
            'pages_count' => 'nullable|integer|min:1',
            'keywords' => 'nullable|string',
            'cover' => 'nullable|image|max:5120', // 5MB
            'file' => 'required|file|mimes:pdf|max:102400', // 100MB
        ]);

        $book = new Book();
        $book->title = $validated['title'];
        $book->slug = Str::slug($validated['title']);
        $book->description = $validated['description'];
        $book->category_id = $validated['category_id'];
        $book->author_id = Auth::id();
        $book->isbn = $validated['isbn'] ?? null;
        $book->doi = $validated['doi'] ?? null;
        $book->price = $validated['price'] ?? 0;
        $book->pages_count = $validated['pages_count'] ?? 0;
        $book->keywords = $validated['keywords'] ?? null;
        $book->status = 'pending';

        // Handle cover upload
        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('books/covers', 'public');
            $book->cover_url = '/storage/' . $coverPath;
        }

        // Handle PDF upload
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('books/files', 'public');
            $book->file_path = '/storage/' . $filePath;
        }

        $book->save();

        return response()->json($book->load('category'), 201);
    }

    public function show(Book $book)
    {
        if ($book->author_id !== Auth::id()) {
            return response()->json(['message' => 'unauthorized'], 403);
        }

        return response()->json($book->load('category'));
    }

    public function update(Request $request, Book $book)
    {
        if ($book->author_id !== Auth::id()) {
            return response()->json(['message' => 'unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'category_id' => 'sometimes|exists:categories,id',
            'isbn' => 'nullable|string|max:50',
            'doi' => 'nullable|string|max:100',
            'price' => 'nullable|numeric|min:0',
            'pages_count' => 'nullable|integer|min:1',
            'keywords' => 'nullable|string',
            'cover' => 'nullable|image|max:5120',
            'file' => 'nullable|file|mimes:pdf|max:102400',
        ]);

        if (isset($validated['title'])) {
            $book->title = $validated['title'];
            $book->slug = Str::slug($validated['title']);
        }

        $book->fill($validated);

        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('books/covers', 'public');
            $book->cover_url = '/storage/' . $coverPath;
        }

        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('books/files', 'public');
            $book->file_path = '/storage/' . $filePath;
        }

        $book->save();

        return response()->json($book->load('category'));
    }

    public function destroy(Book $book)
    {
        if ($book->author_id !== Auth::id()) {
            return response()->json(['message' => 'unauthorized'], 403);
        }

        $book->delete();

        return response()->json(['message' => 'deleted']);
    }
}
