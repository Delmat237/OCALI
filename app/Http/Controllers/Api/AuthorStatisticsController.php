<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthorStatisticsController extends Controller
{
    public function index()
    {
        $authorId = Auth::id();
        
        $books = Book::where('author_id', $authorId)
            ->withCount(['userBooks as reads_count', 'views as views_count'])
            ->with(['transactions' => function($query) {
                $query->select('book_id', DB::raw('SUM(amount) as total_revenue'))
                    ->groupBy('book_id');
            }])
            ->get();

        $totalViews = $books->sum('views_count');
        $totalReads = $books->sum('reads_count');
        $totalRevenue = Auth::user()->getOrCreateWallet()->balance + Auth::user()->getOrCreateWallet()->pending_amount;

        $bookStats = $books->map(function($book) {
            return [
                'id' => $book->id,
                'title' => $book->title,
                'views' => $book->views_count ?? 0,
                'reads' => $book->reads_count ?? 0,
                'revenue' => $book->transactions->first()->total_revenue ?? 0,
            ];
        });

        return response()->json([
            'total_views' => $totalViews,
            'total_reads' => $totalReads,
            'total_revenue' => $totalRevenue,
            'books' => $bookStats,
        ]);
    }
}
