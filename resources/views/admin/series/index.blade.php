<x-admin-layout>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-white">Gestión de {{ $pageTitle }}</h1>
        <button onclick="openModal()" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow-lg transition duration-200 flex items-center">
            <i class="fas fa-plus mr-2"></i> Agregar {{ ucfirst($type) }}
        </button>
    </div>

    <!-- Barra de Búsqueda -->
    <div class="mb-6">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <input type="text" id="searchInput" class="bg-gray-800 border border-gray-700 text-white text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full pl-10 p-3 shadow-sm transition duration-200" placeholder="Buscar en {{ $pageTitle }}...">
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
                        <th class="px-5 py-3 border-b border-gray-700 bg-gray-900 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Temporadas / Caps</th>
                        <th class="px-5 py-3 border-b border-gray-700 bg-gray-900 text-right text-xs font-semibold text-gray-300 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody id="content-table-body" class="divide-y divide-gray-700">
                    <tr><td colspan="5" class="text-center py-4 text-gray-400">Cargando...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Paginación -->
    <div id="pagination" class="mt-6 flex justify-center space-x-2"></div>

    <!-- Modal Agregar/Editar -->
    <div id="contentModal" class="fixed inset-0 bg-black bg-opacity-75 items-center justify-center z-50 backdrop-blur-sm hidden flex" style="display: none;">
        <div class="bg-gray-800 rounded-xl shadow-2xl w-full max-w-2xl mx-4 overflow-hidden border border-gray-700 transform transition-all duration-300 scale-100">
            <!-- Header Modal -->
            <div class="bg-gray-900 px-6 py-4 border-b border-gray-700 flex justify-between items-center">
                <h2 id="modalTitle" class="text-xl font-bold text-white">Agregar {{ ucfirst($type) }}</h2>
                <button onclick="closeModal()" class="text-gray-400 hover:text-white transition">
                    <i class="fas fa-times fa-lg"></i>
                </button>
            </div>

            <!-- Body Modal -->
            <form id="contentForm" class="p-6">
                @csrf
                <input type="hidden" id="contentId" name="id">
                <input type="hidden" name="type" value="{{ $type }}"> <!-- Campo oculto para el tipo -->

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Columna Izquierda -->
                    <div class="md:col-span-2 space-y-4">
                        <div>
                            <label class="block text-gray-400 text-xs font-bold mb-1 uppercase">Título</label>
                            <input type="text" name="title" id="contentTitle" class="w-full bg-gray-700 border border-gray-600 text-white rounded p-2.5 focus:border-red-500 focus:ring-1 focus:ring-red-500 transition" required>
                        </div>

                        <div>
                            <label class="block text-gray-400 text-xs font-bold mb-1 uppercase">Año de Inicio</label>
                            <input type="number" name="release_year" id="contentYear" class="w-full bg-gray-700 border border-gray-600 text-white rounded p-2.5 focus:border-red-500 focus:ring-1 focus:ring-red-500 transition">
                        </div>

                        <div>
                            <label class="block text-gray-400 text-xs font-bold mb-1 uppercase">Descripción</label>
                            <textarea name="description" id="contentDescription" rows="3" class="w-full bg-gray-700 border border-gray-600 text-white rounded p-2.5 focus:border-red-500 focus:ring-1 focus:ring-red-500 transition"></textarea>
                        </div>
                    </div>

                    <!-- Columna Derecha -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-gray-400 text-xs font-bold mb-1 uppercase">URL del Poster</label>
                            <input type="text" name="poster_url" id="contentPoster" class="w-full bg-gray-700 border border-gray-600 text-white rounded p-2.5 focus:border-red-500 focus:ring-1 focus:ring-red-500 transition mb-2">
                            <div class="w-full h-40 bg-gray-900 rounded border border-gray-700 flex items-center justify-center overflow-hidden">
                                <img id="posterPreview" src="" alt="Vista previa" class="h-full object-cover hidden" onerror="this.style.display='none'">
                                <span class="text-gray-600 text-xs" id="posterPlaceholder">Sin imagen</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-gray-400 text-xs font-bold mb-1 uppercase">Géneros</label>
                            <select name="genres[]" id="contentGenres" multiple class="w-full bg-gray-700 border border-gray-600 text-white rounded p-2.5 h-32 focus:border-red-500 focus:ring-1 focus:ring-red-500 transition text-sm">
                                <option value="Acción">Acción</option>
                                <option value="Aventura">Aventura</option>
                                <option value="Ciencia Ficción">Ciencia Ficción</option>
                                <option value="Comedia">Comedia</option>
                                <option value="Drama">Drama</option>
                                <option value="Fantasía">Fantasía</option>
                                <option value="Terror">Terror</option>
                                <option value="Romance">Romance</option>
                                <option value="Documental">Documental</option>
                                <option value="Anime">Anime</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Ctrl+Click para múltiples</p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-3 pt-4 border-t border-gray-700">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-gray-300 rounded transition duration-200">Cancelar</button>
                    <button type="submit" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-bold rounded shadow-lg transition duration-200">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        // Variables globales
        const API_URL = "{{ url('/admin/api/series') }}"; // URL base para la API
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const CONTENT_TYPE = "{{ $type }}"; // 'serie', 'novela', 'anime'
        let currentPage = 1;

        // Inicialización
        document.addEventListener('DOMContentLoaded', () => {
            fetchContent();

            // Búsqueda
            let timeout = null;
            document.getElementById('searchInput').addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    fetchContent(1, this.value);
                }, 500);
            });

            // Formulario
            document.getElementById('contentForm').addEventListener('submit', handleFormSubmit);

            // Preview Poster
            document.getElementById('contentPoster').addEventListener('input', function() {
                const preview = document.getElementById('posterPreview');
                const placeholder = document.getElementById('posterPlaceholder');
                if(this.value){
                    preview.src = this.value;
                    preview.classList.remove('hidden');
                    preview.style.display = 'block';
                    placeholder.classList.add('hidden');
                } else {
                    preview.classList.add('hidden');
                    preview.style.display = 'none';
                    placeholder.classList.remove('hidden');
                }
            });
        });

        // Funciones del Modal (Globales para onclick)
        window.openModal = function() {
            document.getElementById('contentForm').reset();
            document.getElementById('contentId').value = '';
            document.querySelector('[name="type"]').value = CONTENT_TYPE; // Asegurar tipo correcto

            document.getElementById('modalTitle').innerText = 'Agregar ' + capitalize(CONTENT_TYPE);

            // Reset preview
            document.getElementById('posterPreview').classList.add('hidden');
            document.getElementById('posterPreview').style.display = 'none';
            document.getElementById('posterPlaceholder').classList.remove('hidden');

            const modal = document.getElementById('contentModal');
            modal.classList.remove('hidden');
            modal.style.display = 'flex';
        };

        window.closeModal = function() {
            const modal = document.getElementById('contentModal');
            modal.classList.add('hidden');
            modal.style.display = 'none';
        };

        window.editItem = function(item) {
            document.getElementById('contentId').value = item.id;
            document.querySelector('[name="type"]').value = item.type;

            document.getElementById('contentTitle').value = item.title;
            document.getElementById('contentYear').value = item.release_year;
            document.getElementById('contentDescription').value = item.description;
            document.getElementById('contentPoster').value = item.poster_url;

            // Preview
            if(item.poster_url) {
                const preview = document.getElementById('posterPreview');
                preview.src = item.poster_url;
                preview.classList.remove('hidden');
                preview.style.display = 'block';
                document.getElementById('posterPlaceholder').classList.add('hidden');
            }

            // Géneros
            const select = document.getElementById('contentGenres');
            Array.from(select.options).forEach(option => {
                option.selected = item.genres && item.genres.includes(option.value);
            });

            document.getElementById('modalTitle').innerText = 'Editar ' + capitalize(CONTENT_TYPE);

            const modal = document.getElementById('contentModal');
            modal.classList.remove('hidden');
            modal.style.display = 'flex';
        };

        // Lógica AJAX
        async function fetchContent(page = 1, search = '') {
            currentPage = page;
            const tableBody = document.getElementById('content-table-body');
            tableBody.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-gray-400">Cargando...</td></tr>';

            try {
                const response = await fetch(`${API_URL}/list?type=${CONTENT_TYPE}&page=${page}&search=${search}`);
                const data = await response.json();
                renderTable(data.data);
                renderPagination(data);
            } catch (error) {
                console.error('Error:', error);
                tableBody.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-red-400">Error al cargar datos.</td></tr>';
            }
        }

        function renderTable(items) {
            const tableBody = document.getElementById('content-table-body');
            tableBody.innerHTML = '';

            if (items.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-gray-400">No hay contenido registrado.</td></tr>';
                return;
            }

            items.forEach(item => {
                const row = `
                    <tr class="hover:bg-gray-700 transition duration-150">
                        <td class="px-5 py-4 border-b border-gray-700 text-sm">
                            <div class="flex-shrink-0 w-10 h-14">
                                <img class="w-full h-full object-cover rounded" src="${item.poster_url}" alt="${item.title}" onerror="this.src='https://via.placeholder.com/40x56?text=No+Img'">
                            </div>
                        </td>
                        <td class="px-5 py-4 border-b border-gray-700 text-sm text-white font-medium">
                            ${item.title}
                        </td>
                        <td class="px-5 py-4 border-b border-gray-700 text-sm text-gray-300">
                            ${item.release_year || '-'}
                        </td>
                        <td class="px-5 py-4 border-b border-gray-700 text-sm text-gray-300">
                            ${item.seasons_count || 0} Temps / ${item.episodes_count || 0} Caps
                        </td>
                        <td class="px-5 py-4 border-b border-gray-700 text-sm text-right">
                            <button onclick='editItem(${JSON.stringify(item).replace(/'/g, "&#39;")})' class="text-blue-400 hover:text-blue-300 mr-3 transition">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteItem(${item.id})" class="text-red-400 hover:text-red-300 transition">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                tableBody.insertAdjacentHTML('beforeend', row);
            });
        }

        function renderPagination(data) {
            const container = document.getElementById('pagination');
            let html = '';
            if (data.prev_page_url) html += `<button onclick="fetchContent(${data.current_page - 1})" class="px-3 py-1 bg-gray-700 hover:bg-gray-600 rounded text-sm text-white">Anterior</button>`;
            html += `<span class="px-3 py-1 text-gray-400 text-sm">Página ${data.current_page} de ${data.last_page}</span>`;
            if (data.next_page_url) html += `<button onclick="fetchContent(${data.current_page + 1})" class="px-3 py-1 bg-gray-700 hover:bg-gray-600 rounded text-sm text-white">Siguiente</button>`;
            container.innerHTML = html;
        }

        async function handleFormSubmit(e) {
            e.preventDefault();
            const formData = new FormData(e.target);
            const id = formData.get('id');
            const data = Object.fromEntries(formData.entries());

            // Manejar géneros
            const genres = Array.from(document.getElementById('contentGenres').selectedOptions).map(opt => opt.value);
            data.genres = genres;

            const url = id ? `${API_URL}/${id}` : "{{ route('admin.series.store') }}";
            const method = id ? 'PUT' : 'POST';

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    body: JSON.stringify(data)
                });

                if (response.ok) {
                    closeModal();
                    fetchContent(currentPage);
                    showAlert('success', 'Guardado correctamente.');
                } else {
                    const error = await response.json();
                    showAlert('error', 'Error: ' + (error.message || 'Datos inválidos'));
                }
            } catch (error) {
                console.error('Error saving:', error);
                showAlert('error', 'Error de conexión.');
            }
        }

        async function deleteItem(id) {
            if (!confirm('¿Estás seguro de eliminar este registro?')) return;
            try {
                const response = await fetch(`${API_URL}/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': CSRF_TOKEN }
                });
                if (response.ok) {
                    fetchContent(currentPage);
                    showAlert('success', 'Eliminado correctamente.');
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
            container.innerHTML = `<div class="bg-${color}-100 border border-${color}-400 text-${color}-700 px-4 py-3 rounded relative mb-4">${message}</div>`;
            setTimeout(() => { container.innerHTML = ''; }, 3000);
        }

        function capitalize(s) {
            return s && s[0].toUpperCase() + s.slice(1);
        }
    </script>
    @endpush
</x-admin-layout>
