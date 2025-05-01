# MoviesApp 🎬

MoviesApp es una aplicación web desarrollada en Laravel para gestionar películas y series. La aplicación permite crear registros de películas/series con información como nombre, clasificación, fecha de estreno, reseña, temporada y póster. Además, integra una funcionalidad para asignar personajes a cada película, permitiendo incluso la creación "en línea" (a través de un modal) y la eliminación de registros duplicados de personajes.

## Características

- **Gestión de Películas y Series:**  
  Crear, editar, listar y eliminar películas o series.
- **Asociación de Personajes:**  
  Asignar múltiples personajes a cada película o serie mediante una relación Many-to-Many.
- **Modal para Nuevo Personaje:**  
  Crear personajes dinámicamente desde el formulario de películas/series utilizando AJAX.
- **Validación y Subida de Imágenes:**  
  Inclusión de validación de formularios e integración de Storage para los pósteres y fotos de personajes.
- **Diseño Responsive:**  
  Utiliza Bootstrap 5 para un diseño limpio y adaptable.
- **Filtros en la Interfaz:**  
  Navegación con filtros ("Todas", "Anteriores", "Próximos estrenos", "Recientes") para facilitar la búsqueda.

## Tecnologías

- PHP (Laravel Framework)
- MySQL o similar (Base de datos)
- Bootstrap 5 (Diseño y estilos)
- HTML, CSS y JavaScript (Interfaz y funcionalidades en modal con AJAX)
- Git y GitHub para el control de versiones
