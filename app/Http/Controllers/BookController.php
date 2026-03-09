<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookReport;
use App\Models\BookReview;
use App\Models\Category;
use App\Services\BackendBookService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BookController extends Controller
{
    public function __construct()
    {
        // BackendBookService dependency removed
    }

    public function index(Request $request)
    {
        $query = Book::published()->with(['author', 'category']);

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $books = $query->orderByDesc('published_at')->paginate(24)->withQueryString();
        $categories = Category::active()->ordered()->get();

        return view('books.index', compact('books', 'categories'));
    }

    public function show(Book $book)
    {
        if (!$book->isPublished() && (!Auth::check() || (Auth::id() !== $book->author_id && !Auth::user()->isAdmin()))) {
            abort(404);
        }

        $book->load(['author', 'category', 'approvedReviews.user']);
        
        $sessionKey = 'viewed_book_' . $book->id;
        if (!session()->has($sessionKey)) {
            $book->incrementViews();
            session()->put($sessionKey, true);
        }

        $relatedBooks = Book::published()
            ->where('id', '!=', $book->id)
            ->where('category_id', $book->category_id)
            ->with(['author'])
            ->take(6)
            ->get();

        $userBook = null;
        $userReview = null;
        if (Auth::check()) {
            $userBook = Auth::user()->userBooks()->where('book_id', $book->id)->first();
            $userReview = Auth::user()->reviews()->where('book_id', $book->id)->first();
        }

        return view('books.show', compact('book', 'relatedBooks', 'userBook', 'userReview'));
    }

    public function report(Request $request, Book $book)
    {
        $validated = $request->validate([
            'reason' => 'required|in:copyright,inappropriate,spam,plagiarism,other',
            'description' => 'required|string|max:2000',
        ]);

        BookReport::create([
            'user_id' => Auth::id(),
            'book_id' => $book->id,
            'reason' => $validated['reason'],
            'description' => $validated['description'],
        ]);

        return back()->with('success', __('messages.report_submitted'));
    }

    public function storeReview(Request $request, Book $book)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'content' => 'required|string|max:2000',
        ]);

        $existingReview = BookReview::where('user_id', Auth::id())->where('book_id', $book->id)->first();
        if ($existingReview) {
            return back()->with('error', __('messages.already_reviewed'));
        }

        $userBook = Auth::user()->userBooks()->where('book_id', $book->id)->first();

        BookReview::create([
            'user_id' => Auth::id(),
            'book_id' => $book->id,
            'rating' => $validated['rating'],
            'title'   => $validated['title'] ?? null,
            'content' => $validated['content'],
            'is_verified_purchase' => $userBook !== null,
            'status' => 'approved',
        ]);

        $book->updateRating();

        return back()->with('success', __('messages.review_submitted'));
    }

    public function destroyReview(BookReview $review)
    {
        if ($review->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $book = $review->book;
        $review->delete();
        $book->updateRating();

        return back()->with('success', __('messages.review_deleted'));
    }

    // Author methods
    public function myBooks()
    {
        $books = Auth::user()->books()
            ->with('category')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('books.my-books', compact('books'));
    }

    public function create()
    {
        if (!$this->checkAuthorSubscription()) {
            return redirect()->route('pricing')
                ->with('error', __('messages.author_subscription_required'));
        }

        $categories = Category::active()->ordered()->get();
        return view('books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        if (!$this->checkAuthorSubscription()) {
            return redirect()->route('pricing')
                ->with('error', __('messages.author_subscription_required'));
        }

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'category_id' => 'required|exists:categories,id',
            'type'        => 'required|in:book,scientific_review',
            'language'    => 'required|in:fr,en',
            'isbn'        => 'nullable|string|max:20',
            'doi'         => 'nullable|string|max:255',
            'publisher'   => 'nullable|string|max:255',
            'keywords'    => 'nullable|string|max:500',
            'cover_image' => 'required|image|max:5120',
            'book_file'   => 'required|mimes:pdf,epub|max:51200',
            'is_premium'  => 'boolean',
        ]);

        $user  = Auth::user();

        // Save locally - MySQL is source of truth
        $coverPath  = $request->file('cover_image')->store('books/covers', 'public');
        $filePath   = $request->file('book_file')->store('books/files', 'public');

        $pagesCount = 0;
        if ($request->file('book_file')->getClientOriginalExtension() === 'pdf') {
            try {
                $parser = new \Smalot\PdfParser\Parser();
                $pdf    = $parser->parseFile($request->file('book_file')->getPathname());
                $pagesCount = count($pdf->getPages());
            } catch (\Exception $e) {}
        }

        $keywords = $validated['keywords']
            ? array_map('trim', explode(',', $validated['keywords']))
            : null;

        Book::create([
            'author_id'       => $user->id,
            'category_id'     => $validated['category_id'],
            'title'           => $validated['title'],
            'slug'            => Str::slug($validated['title']) . '-' . Str::random(6),
            'description'     => $validated['description'],
            'cover_image'     => $coverPath,
            'file_path'       => $filePath,
            'file_type'       => $request->file('book_file')->getClientOriginalExtension(),
            'pages_count'     => $pagesCount,
            'isbn'            => $validated['isbn'] ?? null,
            'doi'             => $validated['doi'] ?? null,
            'language'        => $validated['language'],
            'publisher'       => $validated['publisher'] ?? null,
            'type'            => $validated['type'],
            'keywords'        => $keywords,
            'status'          => 'pending',
            'is_premium'      => $request->boolean('is_premium'),
        ]);

        return redirect()->route('author.books.index')
            ->with('success', __('messages.book_submitted'));
    }

    public function edit(Book $book)
    {
        if ($book->author_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $categories = Category::active()->ordered()->get();
        return view('books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        if ($book->author_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'category_id' => 'required|exists:categories,id',
            'language' => 'required|in:fr,en',
            'isbn' => 'nullable|string|max:20',
            'doi' => 'nullable|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'keywords' => 'nullable|string|max:500',
            'cover_image' => 'nullable|image|max:5120',
            'is_premium' => 'boolean',
        ]);

        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'language' => $validated['language'],
            'publisher' => $validated['publisher'] ?? null,
            'isbn' => $validated['isbn'] ?? null,
            'doi' => $validated['doi'] ?? null,
            'keywords' => $validated['keywords'] ? array_map('trim', explode(',', $validated['keywords'])) : null,
            'is_premium' => $request->boolean('is_premium'),
        ];

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('books/covers', 'public');
        }

        $book->update($data);

        return redirect()->route('author.books.index')
            ->with('success', __('messages.book_updated'));
    }

    public function destroy(Book $book)
    {
        if ($book->author_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $book->delete();

        return redirect()->route('author.books.index')
            ->with('success', __('messages.book_deleted'));
    }


    private function checkAuthorSubscription()
    {
        // Free period logic: Feb 13, 2026 to Feb 13, 2027
        $freePeriodEnd = \Carbon\Carbon::parse('2027-02-13');
        
        if (now()->gt($freePeriodEnd)) {
            $user = Auth::user();
            if (!$user->activeSubscription || $user->activeSubscription->plan->type !== 'author') {
                return false;
            }
        }
        
        return true;
    }
}
