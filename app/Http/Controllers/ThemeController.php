<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThemeController extends Controller
{
    public function toggle(Request $request)
    {
        $currentTheme = session('theme', 'light');
        $newTheme = $currentTheme === 'light' ? 'dark' : 'light';

        session(['theme' => $newTheme]);

        if (auth()->check()) {
            auth()->user()->update(['theme' => $newTheme]);
        }

        return back();
    }
}
