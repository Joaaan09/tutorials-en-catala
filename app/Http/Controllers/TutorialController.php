<?php

namespace App\Http\Controllers;

use App\Models\Tutorial;

class TutorialController extends Controller
{
    public function index()
    {
        $tutorials = Tutorial::with('steps.images')->paginate(10);
        return view('tutorials.index', compact('tutorials'));
    }

    public function show(Tutorial $tutorial)
    {
        return view('tutorials.show', compact('tutorial'));
    }

    public function search()
    {
        $query = request('q');
        $tutorials = Tutorial::where('title', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->paginate(10);
        
        return view('tutorials.index', compact('tutorials'));
    }
}