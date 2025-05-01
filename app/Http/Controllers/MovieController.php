<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Character;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->input('filter', 'all');

        $query = Movie::query();

        switch ($filter) {
            case 'past':
                $query->past();
                break;
            case 'upcoming':
                $query->upcoming();
                break;
            case 'recent':
                $query->recent();
                break;
        }

        $movies = $query->with('characters')->paginate(12);

        return view('movies.index', [
            'movies' => $movies,
            'currentFilter' => $filter
        ]);
    }

    public function create()
    {
        $characters = Character::all();
        return view('movies.create', compact('characters'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'classification' => 'required|string|max:50',
            'release_date' => 'required|date',
            'review' => 'nullable|string',
            'season' => 'nullable|string',
            'poster' => 'nullable|image|max:2048',
            'characters' => 'nullable|array',
            'characters.*' => 'exists:characters,id'
        ]);

        if ($request->hasFile('poster')) {
            $data['poster_path'] = $request->file('poster')->store('posters', 'public');
        }

        $movie = Movie::create($data);

        if (isset($data['characters'])) {
            $movie->characters()->sync($data['characters']);
        }

        return redirect()->route('movies.index')->with('success', 'Película creada correctamente.');
    }

    public function show(Movie $movie)
    {
        $movie->load('characters');
        return view('movies.show', compact('movie'));
    }

    public function edit(Movie $movie)
    {
        $characters = Character::all();
        return view('movies.edit', compact('movie', 'characters'));
    }

    public function update(Request $request, Movie $movie)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'classification' => 'required|string|max:50',
            'release_date' => 'required|date',
            'review' => 'nullable|string',
            'season' => 'nullable|string',
            'poster' => 'nullable|image|max:2048',
            'characters' => 'nullable|array',
            'characters.*' => 'exists:characters,id'
        ]);

        if ($request->hasFile('poster')) {
            if ($movie->poster_path) {
                Storage::disk('public')->delete($movie->poster_path);
            }
            $data['poster_path'] = $request->file('poster')->store('posters', 'public');
        }

        $movie->update($data);

        if (isset($data['characters'])) {
            $movie->characters()->sync($data['characters']);
        }

        return redirect()->route('movies.index')->with('success', 'Película actualizada correctamente.');
    }

    public function destroy(Movie $movie)
    {
        if ($movie->poster_path) {
            Storage::disk('public')->delete($movie->poster_path);
        }

        $movie->delete();

        return redirect()->route('movies.index')->with('success', 'Película eliminada.');
    }
}