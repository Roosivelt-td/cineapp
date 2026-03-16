# Diseño de Base de Datos Detallado para CineApp (SQLite)

Este documento detalla la estructura de la base de datos actualizada con soporte para autores, comentarios, calificaciones y contadores.

## 1. Tabla: `users` (Usuarios)
- `id`: Identificador único.
- `name`: Nombre completo.
- `email`: Correo electrónico (único).
- `password`: Contraseña.
- `role`: 'admin' o 'user'.
- `profile_photo_path`: Foto de perfil (opcional).
- `timestamps`: `created_at`, `updated_at`.

## 2. Tabla: `movies` (Películas y Videos +18)
Contenido de un solo archivo de video.
- `id`: Identificador único.
- `title`: Título.
- `description`: Sinopsis detallada.
- `poster_url`: Portada.
- `video_url`: Archivo de video.
- `is_adult`: Boolean (Contenido +18).
- `release_year`: Año de lanzamiento.
- `duration`: Duración en minutos.
- `authors`: **JSON** (Array de autores/actores/directores).
- `genres`: **JSON** (Array de géneros: Acción, Drama, etc.).
- `views_count`: Contador de visitas.
- `likes_count`: Contador de "Me gusta".
- `rating_avg`: Promedio de calificación (0-5).
- `timestamps`.

## 3. Tabla: `series` (Series y Novelas)
Contenedor de temporadas.
- `id`: Identificador único.
- `title`: Título.
- `description`: Sinopsis.
- `poster_url`: Portada.
- `type`: 'serie' o 'novela'.
- `release_year`: Año de inicio.
- `authors`: **JSON** (Array de autores/actores).
- `genres`: **JSON** (Array de géneros).
- `is_adult`: Boolean.
- `views_count`: Contador de visitas acumuladas.
- `likes_count`: Contador de "Me gusta".
- `rating_avg`: Promedio de calificación.
- `timestamps`.

## 4. Tabla: `seasons` (Temporadas)
- `id`: Identificador único.
- `series_id`: Relación con `series`.
- `name`: Nombre (ej. "Temporada 1").
- `number`: Número de orden.
- `timestamps`.

## 5. Tabla: `episodes` (Capítulos)
Los videos individuales de las series.
- `id`: Identificador único.
- `season_id`: Relación con `seasons`.
- `title`: Título del capítulo.
- `description`: Descripción del capítulo.
- `number`: Número de capítulo.
- `video_url`: Archivo de video.
- `duration`: Duración.
- `views_count`: Contador de visitas.
- `likes_count`: Contador de "Me gusta".
- `timestamps`.

## 6. Tabla: `comments` (Comentarios)
Tabla polimórfica para comentar en Películas o Capítulos.
- `id`: Identificador único.
- `user_id`: Usuario que comenta.
- `body`: Texto del comentario.
- `commentable_id`: ID del video (Película o Capítulo).
- `commentable_type`: Modelo (ej. `App\Models\Movie` o `App\Models\Episode`).
- `likes_count`: Likes que ha recibido este comentario (opcional).
- `timestamps`.

## 7. Tabla: `likes` (Me Gusta)
Tabla polimórfica para dar "Me gusta" a Películas, Series o Capítulos.
- `id`: Identificador único.
- `user_id`: Usuario que da like.
- `likeable_id`: ID del contenido.
- `likeable_type`: Modelo (Movie, Series, Episode).
- `timestamps`.

## 8. Tabla: `ratings` (Calificaciones)
Para que los usuarios puntúen (1 a 5 estrellas).
- `id`: Identificador único.
- `user_id`: Usuario.
- `rateable_id`: ID del contenido.
- `rateable_type`: Modelo (Movie, Series).
- `score`: Puntuación (1-5).
- `review`: Reseña opcional (texto breve).
- `timestamps`.

---
**Notas Técnicas:**
- **Arrays (Autores, Géneros):** En SQLite y MySQL moderno, usaremos columnas de tipo `JSON` para almacenar listas `['Autor 1', 'Autor 2']` de forma sencilla sin crear tablas extra complejas.
- **Polimorfismo:** Las tablas `comments`, `likes` y `ratings` usan `_id` y `_type` para poder reutilizarse tanto para Películas como para Capítulos/Series sin duplicar tablas.
