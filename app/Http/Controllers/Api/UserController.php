<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function me(Request $request)
    {
        $user = $request->user();
        $user->load(['activeSubscription.plan', 'wallet']);

        return response()->json($user);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'locale' => 'sometimes|in:fr,en',
            'theme' => 'sometimes|in:light,dark',
        ]);

        $user->update($validated);

        return response()->json($user->fresh());
    }
}
