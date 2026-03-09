<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chronicle;
use Illuminate\Http\Request;

class ChronicleController extends Controller
{
    public function index()
    {
        $chronicles = Chronicle::latest()->paginate(10);
        return response()->json($chronicles);
    }

    public function show(Chronicle $chronicle)
    {
        return response()->json($chronicle);
    }
}
