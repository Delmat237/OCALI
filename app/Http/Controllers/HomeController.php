<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    /**
     * Home page
     */
    public function index()
    {
        $featuredBooks = Book::published()
            ->featured()
            ->with(['author', 'category'])
            ->orderByDesc('published_at')
            ->take(8)
            ->get();

        $latestBooks = Book::published()
            ->with(['author', 'category'])
            ->orderByDesc('published_at')
            ->take(12)
            ->get();

        $popularBooks = Book::published()
            ->with(['author', 'category'])
            ->orderByDesc('reads_count')
            ->take(8)
            ->get();

        $categories = Category::active()
            ->ordered()
            ->withCount(['books' => function ($query) {
                $query->published();
            }])
            ->take(8)
            ->get();

        $scientificReviews = Book::published()
            ->ofType('scientific_review')
            ->with(['author'])
            ->orderByDesc('published_at')
            ->take(6)
            ->get();

        return view('home', compact(
            'featuredBooks',
            'latestBooks',
            'popularBooks',
            'categories',
            'scientificReviews'
        ));
    }

    /**
     * Explore page
     */
    public function explore(Request $request)
    {
        $query = Book::published()->with(['author', 'category']);

        // Search
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('author', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Language filter
        if ($request->filled('language')) {
            $query->where('language', $request->language);
        }

        // Sort
        $sort = $request->get('sort', 'latest');
        $query = match ($sort) {
            'popular' => $query->orderByDesc('reads_count'),
            'rating' => $query->orderByDesc('average_rating'),
            'title' => $query->orderBy('title'),
            default => $query->orderByDesc('published_at'),
        };

        $books = $query->paginate(24)->withQueryString();

        $categories = Category::active()->ordered()->get();

        return view('explore', compact('books', 'categories'));
    }

    /**
     * Categories page
     */
    public function categories()
    {
        $categories = Category::active()
            ->ordered()
            ->withCount(['books' => function ($query) {
                $query->published();
            }])
            ->get();

        return view('categories', compact('categories'));
    }

    /**
     * Single category
     */
    public function category(Category $category)
    {
        $books = $category->books()
            ->published()
            ->with(['author'])
            ->orderByDesc('published_at')
            ->paginate(24);

        return view('category', compact('category', 'books'));
    }

    /**
     * Subscribe to newsletter
     */
    public function subscribeNewsletter(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'name' => 'nullable|string|max:255',
        ]);

        $subscriber = NewsletterSubscriber::firstOrCreate(
            ['email' => $request->email],
            [
                'name' => $request->name,
                'user_id' => auth()->id(),
                'unsubscribe_token' => Str::random(32),
                'subscribed_at' => now(),
            ]
        );

        if ($subscriber->unsubscribed_at) {
            $subscriber->update(['unsubscribed_at' => null, 'subscribed_at' => now()]);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('messages.newsletter_subscribed')
            ]);
        }

        return back()->with('success', __('messages.newsletter_subscribed'));
    }

    /**
     * Unsubscribe from newsletter
     */
    public function unsubscribeNewsletter(string $token)
    {
        $subscriber = NewsletterSubscriber::where('unsubscribe_token', $token)->firstOrFail();

        $subscriber->update(['unsubscribed_at' => now()]);

        return view('newsletter-unsubscribed');
    }

    /**
     * Show contact page
     */
    public function showContact()
    {
        return view('pages.contact');
    }

    /**
     * Send contact form message to admin
     */
    public function sendContact(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        $adminEmail = config('mail.from.address', env('MAIL_FROM_ADDRESS', 'admin@ocali.com'));
        $subject    = '[Contact OCaLi] ' . ($validated['subject'] ?? 'Nouveau message de ' . $validated['name']);

        try {
            \Illuminate\Support\Facades\Mail::raw(
                "Nom : {$validated['name']}\nEmail : {$validated['email']}\n\nMessage :\n{$validated['message']}",
                function ($message) use ($adminEmail, $subject, $validated) {
                    $message->to($adminEmail)
                        ->replyTo($validated['email'], $validated['name'])
                        ->subject($subject);
                }
            );

            \Illuminate\Support\Facades\Log::info('Contact form sent', ['from' => $validated['email']]);

            return back()->with('success', __('messages.contact_sent'));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Contact form failed: ' . $e->getMessage());
            return back()->withInput()->with('error', __('messages.contact_error'));
        }
    }
}
