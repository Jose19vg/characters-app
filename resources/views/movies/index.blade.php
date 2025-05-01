@extends('layouts.app')

@section('content')
  <div class="text-center mb-4">
    <h1 class="display-5">Películas y series ({{ ucfirst($currentFilter) }})</h1>
    <a href="{{ route('movies.create') }}" class="btn btn-primary mt-2">+ Añadir película</a>
  </div>

  @if($movies->count())
    <div class="row justify-content-center g-4">
      @foreach($movies as $movie)
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
          <div class="card h-100 shadow-sm">
            @if($movie->poster_path)
              <img src="{{ Storage::url($movie->poster_path) }}" class="card-img-top" alt="{{ $movie->name }}">
            @else
              <svg class="bd-placeholder-img card-img-top text-bg-secondary" width="100%" height="180"
                   xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Sin imagen" 
                   preserveAspectRatio="xMidYMid slice" focusable="false">
                <title>Sin imagen</title>
                <rect width="100%" height="100%"></rect>
                <text x="50%" y="50%" fill="#dee2e6" dy=".3em" text-anchor="middle">Sin imagen</text>
              </svg>
            @endif

            <div class="card-body d-flex flex-column">
              <h5 class="card-title">{{ $movie->name }}</h5>
              <p class="card-text mb-1">
                <small class="text-muted">
                  <strong>Clasif.:</strong> {{ $movie->classification }}
                </small>
              </p>
              <p class="card-text mb-1">
                <small class="text-muted">
                  <strong>Estreno:</strong> {{ $movie->release_date->format('d M Y') }}
                </small>
              </p>
              @if($movie->season)
                <p class="card-text mb-2">
                  <small class="text-muted">
                    <strong>Temporada:</strong> {{ $movie->season }}
                  </small>
                </p>
              @endif
              <p class="card-text flex-grow-1">{{ Str::limit($movie->review, 80) }}</p>

              {{-- Sección de Personajes con botón de borrar en la esquina superior derecha --}}
              @if($movie->characters->count())
                <div class="mb-2">
                  <small class="text-muted">Personajes:</small>
                  <div class="d-flex flex-wrap">
                    @foreach($movie->characters as $character)
                      <div class="position-relative me-2 mb-2">
                        <!-- Botón que representa al personaje -->
                        <button type="button" class="btn btn-outline-secondary btn-sm">
                          <img src="{{ $character->picture_path ? Storage::url($character->picture_path) : asset('images/default-character.png') }}"
                               alt="{{ $character->name }}" class="rounded" style="width:30px; height:30px;">
                          {{ $character->name }}
                        </button>
                        <!-- Botón de borrar posicionado en la esquina superior derecha -->
                        <form action="{{ route('characters.destroy', $character->id) }}" method="POST" 
                              class="position-absolute top-0 end-0" 
                              onsubmit="return confirm('¿Está seguro de borrar este personaje?');">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-danger btn-sm p-0" style="width:20px; height:20px; line-height:14px;">
                            &times;
                          </button>
                        </form>
                      </div>
                    @endforeach
                  </div>
                </div>
              @endif

              <a href="{{ route('movies.show',$movie) }}" class="btn btn-outline-primary mt-auto">
                Ver detalles →
              </a>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <div class="mt-4 d-flex justify-content-center">
      {{ $movies->appends(['filter' => $currentFilter])->links('vendor.pagination.bootstrap-5') }}
    </div>
  @else
    <div class="alert alert-info text-center">No hay películas o series para mostrar.</div>
  @endif
@endsection
