<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chronicle;
use App\Models\ChronicleComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index(Chronicle $chronicle)
    {
        $comments = $chronicle->approvedComments()
            ->with('user')
            ->orderByDesc('created_at')
            ->paginate(20);

        return response()->json($comments);
    }

    public function store(Request $request, Chronicle $chronicle)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment = ChronicleComment::create([
            'user_id' => Auth::id(),
            'chronicle_id' => $chronicle->id,
            'content' => $validated['content'],
            'status' => 'approved', // or pending
        ]);

        return response()->json($comment, 201);
    }
}
