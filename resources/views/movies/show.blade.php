@extends('layouts.app')

@section('content')
  <div class="card shadow-sm mb-4">
    <div class="row g-0">
      <div class="col-md-4">
        @if($movie->poster_path)
          <img src="{{ Storage::url($movie->poster_path) }}" class="img-fluid rounded-start" alt="{{ $movie->name }}">
        @else
          <img src="{{ asset('images/default.jpg') }}" class="img-fluid rounded-start" alt="Sin imagen">
        @endif
      </div>
      <div class="col-md-8">
        <div class="card-body">
          <h2 class="card-title">{{ $movie->name }}</h2>
          <p class="card-text"><small class="text-muted">
            <strong>Clasificación:</strong> {{ $movie->classification }} |
            <strong>Estreno:</strong> {{ $movie->release_date->format('d M Y') }}
            @if($movie->season)
              | <strong>Temporada:</strong> {{ $movie->season }}
            @endif
          </small></p>
          <p class="card-text">{{ $movie->review }}</p>
          <a href="{{ route('movies.edit',$movie) }}" class="btn btn-outline-primary">Editar</a>
          <a href="{{ route('movies.index',['filter'=>'all']) }}" class="btn btn-secondary">Volver</a>
        </div>
      </div>
    </div>
  </div>

  <h3>Personajes</h3>
  <div class="row g-3">
    @foreach($movie->characters as $c)
      <div class="col-6 col-md-4 col-lg-3">
        <div class="card h-100 text-center">
          @if($c->picture_path)
            <img src="{{ Storage::url($c->picture_path) }}" class="card-img-top" alt="{{ $c->name }}">
          @else
            <svg class="bd-placeholder-img card-img-top text-bg-secondary" width="100%" height="120"
                 xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Sin imagen"
                 preserveAspectRatio="xMidYMid slice" focusable="false">
              <title>Sin imagen</title>
              <rect width="100%" height="100%"></rect>
              <text x="50%" y="50%" fill="#dee2e6" dy=".3em" text-anchor="middle">Sin imagen</text>
            </svg>
          @endif
          <div class="card-body">
            <h5 class="card-title">{{ $c->name }}</h5>
            <p class="card-text">{{ Str::limit($c->description, 60) }}</p>
          </div>
        </div>
      </div>
    @endforeach
  </div>
@endsection
