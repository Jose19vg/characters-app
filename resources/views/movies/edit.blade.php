@extends('layouts.app')

@section('content')
<div class="container">
  <h1 class="mb-4">Editar película o serie</h1>

  @if ($errors->any())
    <div class="alert alert-danger">
      <strong>¡Ups!</strong> Hubo algunos problemas con tus datos.<br><br>
      <ul>
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('movies.update', $movie->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <!-- Nombre -->
    <div class="mb-3">
      <label for="name" class="form-label">Nombre</label>
      <input type="text" name="name" class="form-control" value="{{ old('name', $movie->name) }}" required>
    </div>

    <!-- Fecha de estreno -->
    <div class="mb-3">
      <label for="release_date" class="form-label">Fecha de estreno</label>
      <input type="date" name="release_date" class="form-control" value="{{ old('release_date', $movie->release_date->format('Y-m-d')) }}" required>
    </div>

    <!-- Clasificación -->
    <div class="mb-3">
      <label for="classification" class="form-label">Clasificación</label>
      <input type="text" name="classification" class="form-control" value="{{ old('classification', $movie->classification) }}">
    </div>

    <!-- Temporada -->
    <div class="mb-3">
      <label for="season" class="form-label">Temporada (si aplica)</label>
      <input type="text" name="season" class="form-control" value="{{ old('season', $movie->season) }}">
    </div>

    <!-- Póster -->
    <div class="mb-3">
      <label for="poster_path" class="form-label">Póster</label>
      <input type="file" name="poster_path" class="form-control">
      @if ($movie->poster_path)
        <small class="d-block mt-1">
          Actualmente: <img src="{{ Storage::url($movie->poster_path) }}" width="100">
        </small>
      @endif
    </div>

    <!-- Reseña -->
    <div class="mb-3">
      <label for="review" class="form-label">Reseña</label>
      <textarea name="review" class="form-control" rows="4">{{ old('review', $movie->review) }}</textarea>
    </div>

    <!-- Personajes -->
    <div class="mb-4">
      <label class="form-label">Personajes</label>
      <div class="row">
        @foreach($characters as $character)
          <div class="col-md-3 mb-3">
            <div class="form-check">
              <input 
                class="form-check-input" 
                type="checkbox" 
                name="characters[]" 
                value="{{ $character->id }}" 
                id="character_{{ $character->id }}"
                {{ in_array($character->id, old('characters', $movie->characters->pluck('id')->toArray())) ? 'checked' : '' }}>
              <label class="form-check-label d-flex align-items-center" for="character_{{ $character->id }}">
                <img src="{{ $character->picture_path ? Storage::url($character->picture_path) : asset('images/default-character.png') }}" 
                     alt="{{ $character->name }}" 
                     class="img-thumbnail me-2" 
                     style="width: 50px; height: 50px;">
                <span>{{ $character->name }}</span>
              </label>
            </div>
          </div>
        @endforeach
      </div>
      @error('characters')
        <div class="invalid-feedback d-block">{{ $message }}</div>
      @enderror
    </div>

    <button type="submit" class="btn btn-success">Actualizar</button>
    <a href="{{ route('movies.index') }}" class="btn btn-secondary">Cancelar</a>
  </form>
</div>
@endsection
