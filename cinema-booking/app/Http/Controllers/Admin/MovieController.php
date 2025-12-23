<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    
    public function index()
    {
        $movies = Movie::all();
        return view('admin.movies.index', compact('movies'));
    }

   
    public function create()
    {
        return view('admin.movies.create');
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'nullable',
            'duration' => 'required|integer|min:1',
            'poster' => 'nullable|image|max:2048',
        ]);

        $data = $request->only('title', 'description', 'duration');

        if ($request->hasFile('poster')) {
            $data['poster_url'] = $request->file('poster')->store('posters', 'public');
        }

        $movie = Movie::create($data);
        if ($request->expectsJson()) {
            return response()->json($movie, 201);
        }
        return redirect()->route('admin.dashboard')->with('success', 'Фильм создан');
    }

    
    public function edit(Movie $movie)
    {
        return view('admin.movies.edit', compact('movie'));
    }

   
    public function update(Request $request, Movie $movie)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'nullable',
            'duration' => 'required|integer|min:1',
            'poster' => 'nullable|image|max:2048',
        ]);

        $movie->title = $request->title;
        $movie->description = $request->description;
        $movie->duration = $request->duration;

        if ($request->hasFile('poster')) {
            $movie->poster_url = $request->file('poster')->store('posters', 'public');
        }

        $movie->save();

        return redirect()->route('admin.dashboard')->with('success', 'Фильм создан');
    }

  
   public function destroy(Movie $movie)
    {
    
        $movie->sessions()->delete();

        
        $movie->delete();

        return redirect()->route('admin.movies.index');
    }
}
