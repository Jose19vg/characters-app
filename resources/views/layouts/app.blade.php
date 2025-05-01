<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'MoviesApp 🎬')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar con un toque especial en la marca -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <!-- Se agrega un emoji junto a MoviesApp -->
            <a class="navbar-brand" href="{{ route('movies.index') }}">
              🎬 MoviesApp
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
              data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
              aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Aquí se pueden agregar otros enlaces, como los filtros -->
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request('filter') === 'all' ? 'active' : '' }}" 
                           href="{{ route('movies.index', ['filter' => 'all']) }}">Todas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('filter') === 'past' ? 'active' : '' }}" 
                           href="{{ route('movies.index', ['filter' => 'past']) }}">Anteriores</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('filter') === 'upcoming' ? 'active' : '' }}" 
                           href="{{ route('movies.index', ['filter' => 'upcoming']) }}">Próximos estrenos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('filter') === 'recent' ? 'active' : '' }}" 
                           href="{{ route('movies.index', ['filter' => 'recent']) }}">Recientes</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <main class="container">
        @yield('content')
    </main>

    <!-- Scripts de Bootstrap y pila de scripts adicionales -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>

