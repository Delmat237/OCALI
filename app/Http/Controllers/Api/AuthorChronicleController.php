<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chronicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthorChronicleController extends Controller
{
    public function index()
    {
        $chronicles = Chronicle::where('author_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        return response()->json($chronicles);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:5120',
        ]);

        $chronicle = new Chronicle();
        $chronicle->title = $validated['title'];
        $chronicle->slug = Str::slug($validated['title']);
        $chronicle->content = $validated['content'];
        $chronicle->author_id = Auth::id();
        $chronicle->status = 'published';

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('chronicles', 'public');
            $chronicle->image_url = '/storage/' . $imagePath;
        }

        $chronicle->save();

        return response()->json($chronicle, 201);
    }

    public function show(Chronicle $chronicle)
    {
        if ($chronicle->author_id !== Auth::id()) {
            return response()->json(['message' => 'unauthorized'], 403);
        }

        return response()->json($chronicle);
    }

    public function update(Request $request, Chronicle $chronicle)
    {
        if ($chronicle->author_id !== Auth::id()) {
            return response()->json(['message' => 'unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
            'image' => 'nullable|image|max:5120',
        ]);

        if (isset($validated['title'])) {
            $chronicle->title = $validated['title'];
            $chronicle->slug = Str::slug($validated['title']);
        }

        if (isset($validated['content'])) {
            $chronicle->content = $validated['content'];
        }

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('chronicles', 'public');
            $chronicle->image_url = '/storage/' . $imagePath;
        }

        $chronicle->save();

        return response()->json($chronicle);
    }

    public function destroy(Chronicle $chronicle)
    {
        if ($chronicle->author_id !== Auth::id()) {
            return response()->json(['message' => 'unauthorized'], 403);
        }

        $chronicle->delete();

        return response()->json(['message' => 'deleted']);
    }
}
