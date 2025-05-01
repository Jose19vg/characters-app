<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CharacterController extends Controller
{
    /**
     * Muestra una lista paginada de personajes.
     */
    public function index()
    {
        $characters = Character::with('movies')->paginate(12);
        return view('characters.index', compact('characters'));
    }

    /**
     * Muestra el formulario para crear un nuevo personaje.
     */
    public function create()
    {
        $movies = Movie::orderBy('name')->get();
        return view('characters.create', compact('movies'));
    }

    /**
     * Almacena un nuevo personaje en la base de datos.
     * Si la petición es vía AJAX, retorna una respuesta JSON.
     */
    public function store(Request $request)
    {
        // Validación de los datos
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'picture'     => 'nullable|image|max:2048',
            'movies'      => 'nullable|array',
            'movies.*'    => 'exists:movies,id',
        ]);

        // Si se sube una imagen, se guarda en 'storage/app/public/characters'
        if ($request->hasFile('picture')) {
            $data['picture_path'] = $request->file('picture')->store('characters', 'public');
        }

        // Creación del personaje
        $character = Character::create([
            'name'         => $data['name'],
            'description'  => $data['description'] ?? null,
            'picture_path' => $data['picture_path'] ?? null,
        ]);

        // Si se enviaron películas para relacionar, se sincroniza
        if (isset($data['movies'])) {
            $character->movies()->sync($data['movies']);
        }

        // Si la petición es AJAX, devuelve respuesta en JSON
        if ($request->expectsJson()) {
            return response()->json([
                'id'          => $character->id,
                'name'        => $character->name,
                'picture_url' => $character->picture_path
                    ? Storage::url($character->picture_path)
                    : asset('images/default-character.png'),
            ], 201);
        }

        // Si no es AJAX, redirige normalmente
        return redirect()
            ->route('characters.index')
            ->with('success', 'Personaje creado correctamente.');
    }

    /**
     * Muestra el formulario para editar un personaje existente.
     */
    public function edit(Character $character)
    {
        $movies = Movie::orderBy('name')->get();
        return view('characters.edit', compact('character', 'movies'));
    }

    /**
     * Actualiza la información de un personaje.
     */
    public function update(Request $request, Character $character)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'picture'     => 'nullable|image|max:2048',
            'movies'      => 'nullable|array',
            'movies.*'    => 'exists:movies,id',
        ]);

        if ($request->hasFile('picture')) {
            if ($character->picture_path) {
                Storage::disk('public')->delete($character->picture_path);
            }
            $data['picture_path'] = $request->file('picture')->store('characters', 'public');
        }

        $character->update([
            'name'         => $data['name'],
            'description'  => $data['description'] ?? $character->description,
            'picture_path' => $data['picture_path'] ?? $character->picture_path,
        ]);

        $character->movies()->sync($data['movies'] ?? []);

        return redirect()
            ->route('characters.index')
            ->with('success', 'Personaje actualizado.');
    }

    /**
     * Elimina un personaje de la base de datos.
     */
    public function destroy(Character $character)
    {
        if ($character->picture_path) {
            Storage::disk('public')->delete($character->picture_path);
        }
        $character->delete();

        return redirect()
            ->route('characters.index')
            ->with('success', 'Personaje eliminado.');
    }
}

