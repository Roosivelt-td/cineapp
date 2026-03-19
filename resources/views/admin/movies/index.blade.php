<x-admin-layout>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-white">Gestión de Películas</h1>
        <button onclick="openModal()" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow-lg transition duration-200 flex items-center">
            <i class="fas fa-plus mr-2"></i> Agregar Película
        </button>
    </div>

    <!-- Barra de Búsqueda Unificada -->
    <div class="mb-6">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <input type="text" id="searchInput" class="bg-gray-800 border border-gray-700 text-white text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full pl-10 p-3 shadow-sm transition duration-200" placeholder="Buscar por título, género, año, descripción...">
        </div>
    </div>

    <!-- Contenedor de Alertas -->
    <div id="alert-container"></div>

    <!-- Tabla de Datos -->
    <div class="bg-gray-800 shadow-xl rounded-lg overflow-hidden border border-gray-700">
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b border-gray-700 bg-gray-900 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Poster</th>
                        <th class="px-5 py-3 border-b border-gray-700 bg-gray-900 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Título</th>
                        <th class="px-5 py-3 border-b border-gray-700 bg-gray-900 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Año</th>
                        <th class="px-5 py-3 border-b border-gray-700 bg-gray-900 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Géneros</th>
                        <th class="px-5 py-3 border-b border-gray-700 bg-gray-900 text-right text-xs font-semibold text-gray-300 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody id="content-table-body" class="divide-y divide-gray-700">
                    <!-- Filas se generan dinámicamente -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Paginación -->
    <div id="pagination" class="mt-6 flex justify-center space-x-2"></div>

    <!-- Modal Agregar/Editar Película -->
    <div id="contentModal" class="fixed inset-0 bg-black bg-opacity-75 items-center justify-center z-50 backdrop-blur-sm transition-opacity duration-300 hidden flex">
        <div class="bg-gray-800 rounded-xl shadow-2xl w-full max-w-2xl mx-4 overflow-hidden border border-gray-700 transform transition-all duration-300 scale-100">
            <!-- Header Modal -->
            <div class="bg-gray-900 px-6 py-4 border-b border-gray-700 flex justify-between items-center">
                <h2 id="modalTitle" class="text-xl font-bold text-white">Agregar Película</h2>
                <button onclick="closeModal()" class="text-gray-400 hover:text-white transition">
                    <i class="fas fa-times fa-lg"></i>
                </button>
            </div>

            <!-- Body Modal -->
            <form id="contentForm" class="p-6">
                @csrf
                <input type="hidden" id="contentId" name="id" value="">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Columna Izquierda (Inputs) -->
                    <div class="md:col-span-2 space-y-4">
                        <div>
                            <label class="block text-gray-400 text-xs font-bold mb-1 uppercase">Título</label>
                            <input type="text" id="contentTitle" name="title" class="w-full bg-gray-700 border border-gray-600 text-white rounded p-2.5 focus:border-red-500 focus:ring-1 focus:ring-red-500 transition" required>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-400 text-xs font-bold mb-1 uppercase">Año</label>
                                <input type="number" id="contentYear" name="release_year" class="w-full bg-gray-700 border border-gray-600 text-white rounded p-2.5 focus:border-red-500 focus:ring-1 focus:ring-red-500 transition">
                            </div>
                            <div>
                                <label class="block text-gray-400 text-xs font-bold mb-1 uppercase">Duración</label>
                                <input type="text" id="contentDuration" name="duration" placeholder="Ej: 135" class="w-full bg-gray-700 border border-gray-600 text-white rounded p-2.5 focus:border-red-500 focus:ring-1 focus:ring-red-500 transition">
                            </div>
                        </div>

                        <div>
                            <label class="block text-gray-400 text-xs font-bold mb-1 uppercase">Descripción</label>
                            <textarea id="contentDescription" name="description" rows="3" class="w-full bg-gray-700 border border-gray-600 text-white rounded p-2.5 focus:border-red-500 focus:ring-1 focus:ring-red-500 transition"></textarea>
                        </div>

                        <div id="videoUrlField" style="display: block;">
                            <label class="block text-gray-400 text-xs font-bold mb-1 uppercase">URL del Video (Embed/MP4)</label>
                            <input type="text" id="contentVideo" name="video_url" class="w-full bg-gray-700 border border-gray-600 text-white rounded p-2.5 focus:border-red-500 focus:ring-1 focus:ring-red-500 transition">
                        </div>
                    </div>

                    <!-- Columna Derecha (Poster y Géneros) -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-gray-400 text-xs font-bold mb-1 uppercase">URL del Poster</label>
                            <input type="text" id="contentPoster" name="poster_url" class="w-full bg-gray-700 border border-gray-600 text-white rounded p-2.5 focus:border-red-500 focus:ring-1 focus:ring-red-500 transition mb-2">
                            <div class="w-full h-40 bg-gray-900 rounded border border-gray-700 flex items-center justify-center overflow-hidden">
                                <img id="posterPreview" src="" alt="Vista previa" class="h-full object-cover hidden" onerror="this.style.display='none'">
                                <span class="text-gray-600 text-xs" id="posterPlaceholder">Sin imagen</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-gray-400 text-xs font-bold mb-1 uppercase">Géneros</label>
                            <select id="contentGenres" name="genres[]" multiple class="w-full bg-gray-700 border border-gray-600 text-white rounded p-2.5 h-32 focus:border-red-500 focus:ring-1 focus:ring-red-500 transition text-sm">
                                <option value="Acción">Acción</option>
                                <option value="Aventura">Aventura</option>
                                <option value="Ciencia Ficción">Ciencia Ficción</option>
                                <option value="Comedia">Comedia</option>
                                <option value="Drama">Drama</option>
                                <option value="Fantasía">Fantasía</option>
                                <option value="Terror">Terror</option>
                                <option value="Romance">Romance</option>
                                <option value="Documental">Documental</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Ctrl+Click para múltiples</p>
                        </div>
                    </div>
                </div>

                <!-- Footer Modal -->
                <div class="mt-8 flex justify-end space-x-3 pt-4 border-t border-gray-700">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-gray-300 rounded transition duration-200">Cancelar</button>
                    <button type="submit" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-bold rounded shadow-lg transition duration-200">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        const API_URL = "{{ url('/api') }}";
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    </script>
    <script>
        // Lógica de JavaScript para el panel de administración
        let currentPage = 1;

        document.addEventListener('DOMContentLoaded', () => {
            fetchMovies();

            // Búsqueda en tiempo real (debounce)
            let timeout = null;
            document.getElementById('searchInput').addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    fetchMovies(1, this.value);
                }, 500);
            });

            // Formulario
            document.getElementById('contentForm').addEventListener('submit', handleFormSubmit);
        });

        async function fetchMovies(page = 1, search = '') {
            currentPage = page;
            const tableBody = document.getElementById('content-table-body');
            tableBody.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-gray-400">Cargando...</td></tr>';

            try {
                // Usamos la API interna que definiremos
                const response = await fetch(`${API_URL}/admin/movies/list?page=${page}&search=${search}`);
                const data = await response.json();

                renderTable(data.data);
                renderPagination(data);
            } catch (error) {
                console.error('Error fetching movies:', error);
                tableBody.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-red-400">Error al cargar datos.</td></tr>';
            }
        }

        function renderTable(movies) {
            const tableBody = document.getElementById('content-table-body');
            tableBody.innerHTML = '';

            if (movies.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-gray-400">No se encontraron resultados.</td></tr>';
                return;
            }

            movies.forEach(movie => {
                const genres = Array.isArray(movie.genres) ? movie.genres.join(', ') : (movie.genres || '-');
                const row = `
                    <tr class="hover:bg-gray-700 transition duration-150">
                        <td class="px-5 py-4 border-b border-gray-700 text-sm">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-10 h-14">
                                    <img class="w-full h-full object-cover rounded" src="${movie.poster_url}" alt="${movie.title}" onerror="this.src='https://via.placeholder.com/40x56?text=No+Img'">
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 border-b border-gray-700 text-sm">
                            <p class="text-white whitespace-no-wrap font-medium">${movie.title}</p>
                        </td>
                        <td class="px-5 py-4 border-b border-gray-700 text-sm">
                            <p class="text-gray-300 whitespace-no-wrap">${movie.release_year || '-'}</p>
                        </td>
                        <td class="px-5 py-4 border-b border-gray-700 text-sm">
                            <span class="relative inline-block px-3 py-1 font-semibold text-green-900 leading-tight">
                                <span aria-hidden class="absolute inset-0 bg-green-200 opacity-50 rounded-full"></span>
                                <span class="relative text-green-100 text-xs">${genres}</span>
                            </span>
                        </td>
                        <td class="px-5 py-4 border-b border-gray-700 text-sm text-right">
                            <button onclick='editMovie(${JSON.stringify(movie).replace(/'/g, "&#39;")})' class="text-blue-400 hover:text-blue-300 mr-3 transition">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteMovie(${movie.id})" class="text-red-400 hover:text-red-300 transition">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                tableBody.insertAdjacentHTML('beforeend', row);
            });
        }

        function renderPagination(data) {
            const paginationContainer = document.getElementById('pagination');
            let html = '';

            if (data.prev_page_url) {
                html += `<button onclick="fetchMovies(${data.current_page - 1})" class="px-3 py-1 bg-gray-700 hover:bg-gray-600 rounded text-sm text-white">Anterior</button>`;
            }

            html += `<span class="px-3 py-1 text-gray-400 text-sm">Página ${data.current_page} de ${data.last_page}</span>`;

            if (data.next_page_url) {
                html += `<button onclick="fetchMovies(${data.current_page + 1})" class="px-3 py-1 bg-gray-700 hover:bg-gray-600 rounded text-sm text-white">Siguiente</button>`;
            }

            paginationContainer.innerHTML = html;
        }

        // --- Funciones del Modal ---
        function openModal() {
            document.getElementById('contentForm').reset();
            document.getElementById('contentId').value = '';
            document.getElementById('modalTitle').innerText = 'Agregar Película';
            document.getElementById('posterPreview').classList.add('hidden');
            document.getElementById('posterPlaceholder').classList.remove('hidden');

            document.getElementById('contentModal').classList.remove('hidden');
            document.getElementById('contentModal').classList.add('flex');
        }

        function closeModal() {
            document.getElementById('contentModal').classList.add('hidden');
            document.getElementById('contentModal').classList.remove('flex');
        }

        function editMovie(movie) {
            document.getElementById('contentId').value = movie.id;
            document.getElementById('contentTitle').value = movie.title;
            document.getElementById('contentYear').value = movie.release_year;
            document.getElementById('contentDuration').value = movie.duration;
            document.getElementById('contentDescription').value = movie.description;
            document.getElementById('contentVideo').value = movie.video_url;
            document.getElementById('contentPoster').value = movie.poster_url;

            // Previsualización del poster
            if(movie.poster_url) {
                document.getElementById('posterPreview').src = movie.poster_url;
                document.getElementById('posterPreview').classList.remove('hidden');
                document.getElementById('posterPlaceholder').classList.add('hidden');
            }

            // Seleccionar géneros (select multiple)
            const select = document.getElementById('contentGenres');
            Array.from(select.options).forEach(option => {
                option.selected = movie.genres && movie.genres.includes(option.value);
            });

            document.getElementById('modalTitle').innerText = 'Editar Película';
            document.getElementById('contentModal').classList.remove('hidden');
            document.getElementById('contentModal').classList.add('flex');
        }

        async function handleFormSubmit(e) {
            e.preventDefault();
            const formData = new FormData(e.target);
            const id = formData.get('id');
            const data = Object.fromEntries(formData.entries());

            // Manejar select multiple para géneros
            const genres = Array.from(document.getElementById('contentGenres').selectedOptions).map(opt => opt.value);
            data.genres = genres; // Convertir a array simple ["Acción", "Drama"]

            const url = id ? `${API_URL}/admin/movies/${id}` : `${API_URL}/admin/movies`;
            const method = id ? 'PUT' : 'POST';

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data)
                });

                if (response.ok) {
                    closeModal();
                    fetchMovies(currentPage); // Recargar tabla
                    showAlert('success', 'Película guardada correctamente.');
                } else {
                    const errorData = await response.json();
                    showAlert('error', 'Error al guardar: ' + (errorData.message || 'Datos inválidos'));
                }
            } catch (error) {
                console.error('Error saving movie:', error);
                showAlert('error', 'Error de conexión.');
            }
        }

        async function deleteMovie(id) {
            if(!confirm('¿Estás seguro de eliminar esta película?')) return;

            try {
                const response = await fetch(`${API_URL}/admin/movies/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (response.ok) {
                    fetchMovies(currentPage);
                    showAlert('success', 'Película eliminada.');
                } else {
                    showAlert('error', 'No se pudo eliminar.');
                }
            } catch (error) {
                showAlert('error', 'Error de conexión.');
            }
        }

        function showAlert(type, message) {
            const container = document.getElementById('alert-container');
            const color = type === 'success' ? 'green' : 'red';
            const html = `
                <div class="bg-${color}-100 border border-${color}-400 text-${color}-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">${message}</span>
                </div>
            `;
            container.innerHTML = html;
            setTimeout(() => { container.innerHTML = ''; }, 3000);
        }

        // Script adicional para vista previa del poster en tiempo real
        document.getElementById('contentPoster').addEventListener('input', function() {
            const preview = document.getElementById('posterPreview');
            const placeholder = document.getElementById('posterPlaceholder');
            if(this.value){
                preview.src = this.value;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            } else {
                preview.classList.add('hidden');
                placeholder.classList.remove('hidden');
            }
        });
    </script>
    @endpush
</x-admin-layout>
