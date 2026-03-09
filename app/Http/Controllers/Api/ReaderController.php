<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\ReadingSession;
use App\Models\UserBook;
use Illuminate\Http\Request;

class ReaderController extends Controller
{
    public function updateProgress(Request $request, Book $book)
    {
        $validated = $request->validate([
            'current_page' => 'required|integer|min:0',
            'reading_time' => 'nullable|integer|min:0',
        ]);

        $userBook = UserBook::where('user_id', $request->user()->id)
            ->where('book_id', $book->id)
            ->firstOrFail();

        $userBook->updateProgress($validated['current_page']);

        if (isset($validated['reading_time']) && $validated['reading_time'] > 0) {
            $userBook->addReadingTime($validated['reading_time']);

            ReadingSession::create([
                'user_book_id' => $userBook->id,
                'user_id' => $request->user()->id,
                'book_id' => $book->id,
                'started_at' => now()->subMinutes($validated['reading_time']),
                'ended_at' => now(),
                'duration_minutes' => $validated['reading_time'],
                'pages_read' => max(0, $validated['current_page'] - ($userBook->current_page ?? 0)),
                'start_page' => $userBook->current_page ?? 0,
                'end_page' => $validated['current_page'],
            ]);
        }

        return response()->json($userBook->fresh());
    }
}
