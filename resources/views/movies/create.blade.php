@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
  <div class="card-header">
    <h4 class="mb-0">Añadir nueva película o serie</h4>
  </div>
  <div class="card-body">
    <form action="{{ route('movies.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <!-- Campo: Nombre -->
      <div class="mb-3">
        <label class="form-label">Nombre</label>
        <input type="text" name="name" value="{{ old('name') }}"
               class="form-control @error('name') is-invalid @enderror">
        @error('name')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <!-- Campo: Clasificación -->
      <div class="mb-3">
        <label class="form-label">Clasificación</label>
        <input type="text" name="classification" value="{{ old('classification') }}"
               class="form-control @error('classification') is-invalid @enderror">
        @error('classification')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <!-- Campo: Fecha de estreno -->
      <div class="mb-3">
        <label class="form-label">Fecha de estreno</label>
        <input type="date" name="release_date" value="{{ old('release_date') }}"
               class="form-control @error('release_date') is-invalid @enderror">
        @error('release_date')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <!-- Campo: Reseña -->
      <div class="mb-3">
        <label class="form-label">Reseña</label>
        <textarea name="review" rows="3" class="form-control @error('review') is-invalid @enderror">{{ old('review') }}</textarea>
        @error('review')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <!-- Campo: Temporada -->
      <div class="mb-3">
        <label class="form-label">Temporada (opcional)</label>
        <input type="number" name="season" min="1" value="{{ old('season') }}"
               class="form-control @error('season') is-invalid @enderror">
        @error('season')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <!-- Campo: Póster -->
      <div class="mb-3">
        <label class="form-label">Póster (imagen)</label>
        <input type="file" name="poster" class="form-control @error('poster') is-invalid @enderror">
        @error('poster')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <!-- Sección: Selección de Personajes -->
      <div class="mb-4">
        <label class="form-label">Personajes</label>
        <div class="row" id="characters-container">
          @foreach($characters as $character)
            <div class="col-md-3 mb-3">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="characters[]"
                       value="{{ $character->id }}" id="character_{{ $character->id }}"
                       {{ in_array($character->id, old('characters', [])) ? 'checked' : '' }}>
                <label class="form-check-label d-flex align-items-center" for="character_{{ $character->id }}">
                  <img src="{{ $character->picture_path ? Storage::url($character->picture_path) : asset('images/default-character.png') }}"
                       alt="{{ $character->name }}" class="img-thumbnail me-2" style="width: 50px; height: 50px;">
                  <span>{{ $character->name }}</span>
                </label>
              </div>
            </div>
          @endforeach
        </div>
        @error('characters')
          <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror

        <!-- Botón para agregar un nuevo personaje (abre el modal) -->
        <button type="button" class="btn btn-secondary mt-2" data-bs-toggle="modal" data-bs-target="#newCharacterModal">
          Agregar Nuevo Personaje
        </button>
      </div>

      <button type="submit" class="btn btn-primary">Guardar</button>
      <a href="{{ route('movies.index', ['filter' => 'all']) }}" class="btn btn-secondary">Cancelar</a>
    </form>
  </div>
</div>

<!-- Modal para crear nuevo personaje -->
<div class="modal fade" id="newCharacterModal" tabindex="-1" aria-labelledby="newCharacterModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newCharacterModalLabel">Nuevo Personaje</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form id="newCharacterForm">
          @csrf
          <div class="mb-3">
            <label for="characterName" class="form-label">Nombre</label>
            <input type="text" class="form-control" name="name" id="characterName" required>
          </div>
          <div class="mb-3">
            <label for="characterPicture" class="form-label">Foto</label>
            <input type="file" class="form-control" name="picture" id="characterPicture">
          </div>
          <div class="mb-3">
            <label for="characterDescription" class="form-label">Descripción</label>
            <textarea class="form-control" name="description" id="characterDescription" rows="3"></textarea>
          </div>
        </form>
        <div id="characterFormAlert" class="alert d-none"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" id="saveCharacterButton" class="btn btn-primary">Guardar Personaje</button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  // Listener para el botón "Guardar Personaje" del modal
  document.getElementById('saveCharacterButton').addEventListener('click', async function() {
    console.log('Se hizo click en Guardar Personaje'); // Depuración
    const form = document.getElementById('newCharacterForm');
    const formData = new FormData(form);

    try {
      const response = await fetch("{{ route('characters.store') }}", {
        method: "POST",
        body: formData,
        headers: {
          'X-CSRF-TOKEN': "{{ csrf_token() }}",
          'Accept': 'application/json'
        }
      });
      const result = await response.json();

      if (response.ok) {
        // Opcional: evitar duplicados, si ya existe un registro con ese id
        if (document.getElementById("character_" + result.id)) {
          alert("El personaje ya fue agregado.");
        } else {
          const container = document.getElementById('characters-container');
          const div = document.createElement('div');
          div.classList.add('col-md-3', 'mb-3');
          div.innerHTML = `
            <div class="position-relative">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="characters[]" id="character_${result.id}" value="${result.id}" checked>
                <label class="form-check-label d-flex align-items-center" for="character_${result.id}">
                  <img src="${result.picture_url}" alt="${result.name}" class="img-thumbnail me-2" style="width:50px; height:50px;">
                  <span>${result.name}</span>
                </label>
              </div>
              <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 remove-char" onclick="this.closest('.col-md-3').remove()" title="Eliminar registro">&times;</button>
            </div>
          `;
          container.appendChild(div);
        }
        form.reset();
        // Oculta el modal usando Bootstrap
        const modalEl = document.getElementById('newCharacterModal');
        const modal = bootstrap.Modal.getInstance(modalEl);
        modal.hide();
      } else {
        console.error("Error en la respuesta:", result);
        const alertDiv = document.getElementById('characterFormAlert');
        alertDiv.classList.remove('d-none', 'alert-success');
        alertDiv.classList.add('alert-danger');
        alertDiv.innerText = result.message || 'Error al guardar el personaje.';
      }
    } catch (error) {
      console.error("Error al enviar la petición", error);
      const alertDiv = document.getElementById('characterFormAlert');
      alertDiv.classList.remove('d-none', 'alert-success');
      alertDiv.classList.add('alert-danger');
      alertDiv.innerText = 'Error de red u otro.';
    }
  });
</script>
@endpush

@endsection
