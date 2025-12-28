<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
       
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'title'       => 'required|string|max:255',
                'description' => 'nullable|string',
                'duration'    => 'required|integer|min:1',
                'poster'      => 'nullable|image|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $data = $request->only('title', 'description', 'duration');

            if ($request->hasFile('poster')) {
                $data['poster_url'] = $request->file('poster')->store('posters', 'public');
            }

            $movie = Movie::create($data);

            return response()->json($movie, 201);
        }

        
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration'    => 'required|integer|min:1',
            'poster'      => 'nullable|image|max:2048',
        ]);

        $data = $request->only('title', 'description', 'duration');

        if ($request->hasFile('poster')) {
            $data['poster_url'] = $request->file('poster')->store('posters', 'public');
        }

        Movie::create($data);

        return redirect()->route('admin.dashboard')->with('success', 'Фильм создан');
    }

    public function edit(Movie $movie)
    {
        return view('admin.movies.edit', compact('movie'));
    }

    public function update(Request $request, Movie $movie)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration'    => 'required|integer|min:1',
            'poster'      => 'nullable|image|max:2048',
        ]);

        $movie->title = $request->title;
        $movie->description = $request->description;
        $movie->duration = $request->duration;

        if ($request->hasFile('poster')) {
            $movie->poster_url = $request->file('poster')->store('posters', 'public');
        }

        $movie->save();

        return redirect()->route('admin.dashboard')->with('success', 'Фильм обновлён');
    }

    public function destroy(Request $request, Movie $movie)
    {
      
        if (method_exists($movie, 'sessions')) {
            $movie->sessions()->delete();
        }

        $movie->delete();

        
        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.dashboard')->with('success', 'Фильм удалён');
    }
}
