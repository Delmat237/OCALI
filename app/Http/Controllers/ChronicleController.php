<?php

namespace App\Http\Controllers;

use App\Models\Chronicle;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ChronicleController extends Controller
{
    public function index()
    {
        $chronicles = Chronicle::published()
            ->with(['author', 'book'])
            ->orderByDesc('published_at')
            ->paginate(12);

        return view('chronicles.index', compact('chronicles'));
    }

    public function show(Chronicle $chronicle)
    {
        if ($chronicle->status !== 'published' && (!Auth::check() || Auth::id() !== $chronicle->user_id)) {
            abort(404);
        }

        $chronicle->load(['author', 'book', 'approvedComments.user']);
        $chronicle->incrementViews();

        return view('chronicles.show', compact('chronicle'));
    }

    // Author methods
    public function myChronicles()
    {
        $chronicles = Auth::user()->chronicles()
            ->with('book')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('chronicles.my-chronicles', compact('chronicles'));
    }

    public function create()
    {
        $books = Auth::user()->publishedBooks()->get();
        return view('chronicles.create', compact('books'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:50000',
            'book_id' => 'nullable|exists:books,id',
            'cover_image' => 'nullable|image|max:5120',
        ]);

        $data = [
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']) . '-' . Str::random(6),
            'content' => $validated['content'],
            'excerpt' => Str::limit(strip_tags($validated['content']), 200),
            'book_id' => $validated['book_id'] ?? null,
            'status' => 'published',
            'published_at' => now(),
        ];

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('chronicles/covers', 'public');
        }

        Chronicle::create($data);

        return redirect()->route('author.chronicles.index')
            ->with('success', __('messages.chronicle_created'));
    }

    public function edit(Chronicle $chronicle)
    {
        if ($chronicle->user_id !== Auth::id()) {
            abort(403);
        }

        $books = Auth::user()->publishedBooks()->get();
        return view('chronicles.edit', compact('chronicle', 'books'));
    }

    public function update(Request $request, Chronicle $chronicle)
    {
        if ($chronicle->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:50000',
            'book_id' => 'nullable|exists:books,id',
            'cover_image' => 'nullable|image|max:5120',
        ]);

        $data = [
            'title' => $validated['title'],
            'content' => $validated['content'],
            'excerpt' => Str::limit(strip_tags($validated['content']), 200),
            'book_id' => $validated['book_id'] ?? null,
        ];

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('chronicles/covers', 'public');
        }

        $chronicle->update($data);

        return redirect()->route('author.chronicles.index')
            ->with('success', __('messages.chronicle_updated'));
    }

    public function destroy(Chronicle $chronicle)
    {
        if ($chronicle->user_id !== Auth::id()) {
            abort(403);
        }

        $chronicle->delete();

        return redirect()->route('author.chronicles.index')
            ->with('success', __('messages.chronicle_deleted'));
    }
}
