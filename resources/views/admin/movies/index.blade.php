<x-admin-layout>
    <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-4">
        <div>
            <h1 class="text-4xl font-black text-white uppercase tracking-tighter italic">CineApp <span class="text-red-600">Admin</span></h1>
            <p class="text-gray-500 text-[10px] font-black uppercase tracking-[0.3em] mt-1">Movies Control Center</p>
        </div>
        <button onclick="openModal()" class="bg-red-600 hover:bg-red-700 text-white font-black py-4 px-10 rounded-2xl shadow-2xl shadow-red-600/40 transition-all duration-300 flex items-center transform hover:-translate-y-1 active:scale-95">
            <i class="fas fa-plus-circle mr-3"></i> AGREGAR PELÍCULA
        </button>
    </div>

    <!-- Buscador -->
    <div class="mb-10 relative group">
        <div class="absolute inset-y-0 left-0 flex items-center pl-6 pointer-events-none">
            <i class="fas fa-search text-gray-500 group-focus-within:text-red-500 transition-colors"></i>
        </div>
        <input type="text" id="searchInput" class="bg-gray-800/40 border border-gray-700/50 text-white text-sm rounded-[24px] focus:ring-2 focus:ring-red-600 block w-full pl-16 p-5 backdrop-blur-xl transition-all placeholder-gray-600 shadow-2xl" placeholder="Buscar por título, género o año...">
    </div>

    <!-- Contenedor de Alertas Flotantes -->
    <div id="alert-container" class="fixed top-8 right-8 z-[200] space-y-4 w-80"></div>

    <!-- Tabla Minimalista -->
    <div class="bg-gray-900/40 backdrop-blur-2xl rounded-[32px] overflow-hidden border border-gray-800/50 shadow-2xl">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-black/20 text-left">
                        <th class="px-10 py-6 text-[10px] font-black text-gray-500 uppercase tracking-widest">Póster</th>
                        <th class="px-10 py-6 text-[10px] font-black text-gray-500 uppercase tracking-widest">Información</th>
                        <th class="px-10 py-6 text-[10px] font-black text-gray-500 uppercase tracking-widest">Géneros</th>
                        <th class="px-10 py-6 text-right text-[10px] font-black text-gray-500 uppercase tracking-widest">Acciones</th>
                    </tr>
                </thead>
                <tbody id="content-table-body" class="divide-y divide-gray-800/30">
                    <!-- Filas Dinámicas -->
                </tbody>
            </table>
        </div>
    </div>

    <div id="pagination" class="mt-10 flex justify-center"></div>

    <!-- Modal Transparente -->
    <div id="contentModal" class="fixed inset-0 bg-black/95 items-center justify-center z-[150] backdrop-blur-xl hidden p-4 overflow-y-auto">
        <div class="bg-gray-900/90 border border-gray-700/50 rounded-[48px] shadow-[0_0_80px_rgba(0,0,0,0.6)] w-full max-w-5xl transform transition-all duration-500 scale-90 opacity-0" id="modalContainer">

            <div class="px-12 py-10 border-b border-gray-800/50 flex justify-between items-center">
                <h2 id="modalTitle" class="text-4xl font-black text-white uppercase tracking-tighter italic">Nueva Película</h2>
                <button onclick="closeModal()" class="text-gray-400 hover:text-white transition-all bg-gray-800/50 hover:bg-red-600 w-14 h-14 rounded-2xl flex items-center justify-center group">
                    <i class="fas fa-times text-xl group-hover:rotate-90 transition-transform"></i>
                </button>
            </div>

            <form id="contentForm" class="p-12" onsubmit="handleFormSubmit(event)">
                @csrf
                <input type="hidden" id="contentId" name="id" value="">

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-16">
                    <div class="space-y-8">
                        <div>
                            <label class="block text-gray-500 text-[10px] font-black uppercase tracking-widest mb-3">Póster Portada (URL)</label>
                            <input type="text" id="contentPoster" name="poster_url" class="w-full bg-gray-800/50 border border-gray-700/50 text-white rounded-2xl p-4 focus:ring-2 focus:ring-red-600 transition-all text-sm" placeholder="https://...">
                        </div>
                        <div class="aspect-[2/3] w-full bg-gray-800/30 rounded-[32px] border-2 border-dashed border-gray-700 flex flex-col items-center justify-center overflow-hidden relative shadow-inner group">
                            <img id="posterPreview" src="" class="w-full h-full object-cover hidden">
                            <div id="posterPlaceholder" class="text-center opacity-40 group-hover:opacity-100 transition-opacity">
                                <i class="fas fa-image text-6xl mb-4"></i>
                                <p class="text-[10px] font-black uppercase tracking-widest">Vista Previa</p>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-2 space-y-8">
                        <div>
                            <label class="block text-gray-500 text-[10px] font-black uppercase tracking-widest mb-4">Título Cinematográfico</label>
                            <input type="text" id="contentTitle" name="title" class="w-full bg-gray-800/50 border border-gray-700/50 text-white rounded-[24px] p-6 focus:ring-2 focus:ring-red-600 transition-all text-2xl font-black italic shadow-inner" placeholder="Ej: Oppenheimer" required>
                        </div>

                        <div class="grid grid-cols-2 gap-8">
                            <div>
                                <label class="block text-gray-500 text-[10px] font-black uppercase tracking-widest mb-3">Año</label>
                                <input type="number" id="contentYear" name="release_year" class="w-full bg-gray-800/50 border border-gray-700/50 text-white rounded-2xl p-4 focus:ring-2 focus:ring-red-600 transition-all" placeholder="2024">
                            </div>
                            <div>
                                <label class="block text-gray-500 text-[10px] font-black uppercase tracking-widest mb-3">Duración (Mins)</label>
                                <input type="number" id="contentDuration" name="duration" class="w-full bg-gray-800/50 border border-gray-700/50 text-white rounded-2xl p-4 focus:ring-2 focus:ring-red-600 transition-all" placeholder="120">
                            </div>
                        </div>

                        <div>
                            <label class="block text-gray-500 text-[10px] font-black uppercase tracking-widest mb-3">Sinopsis del Contenido</label>
                            <textarea id="contentDescription" name="description" rows="4" class="w-full bg-gray-800/50 border border-gray-700/50 text-white rounded-[24px] p-6 focus:ring-2 focus:ring-red-600 transition-all text-sm leading-relaxed" placeholder="Describe brevemente la trama..."></textarea>
                        </div>

                        <div>
                            <label class="block text-gray-500 text-[10px] font-black uppercase tracking-widest mb-3">Fuente de Video (URL)</label>
                            <input type="text" id="contentVideo" name="video_url" class="w-full bg-gray-800/50 border border-gray-700/50 text-white rounded-2xl p-4 focus:ring-2 focus:ring-red-600 transition-all text-sm" placeholder="URL directa o Embed">
                        </div>

                        <div>
                            <label class="block text-gray-500 text-[10px] font-black uppercase tracking-widest mb-5">Categorías / Géneros</label>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 max-h-56 overflow-y-auto p-6 bg-gray-800/20 rounded-[32px] custom-scrollbar border border-gray-700/30 shadow-inner">
                                @foreach($genres as $genre)
                                    <label class="flex items-center space-x-3 cursor-pointer group hover:bg-white/5 p-3 rounded-xl transition-all">
                                        <input type="checkbox" name="genres[]" value="{{ $genre->id }}" class="genre-checkbox form-checkbox h-5 w-5 text-red-600 bg-gray-950 border-gray-700 rounded-lg focus:ring-red-600 transition-all cursor-pointer">
                                        <span class="text-[11px] font-black text-gray-500 group-hover:text-white transition-colors uppercase tracking-tighter">{{ $genre->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-16 flex justify-end space-x-5 pt-10 border-t border-gray-800/50">
                    <button type="button" onclick="closeModal()" class="px-10 py-5 bg-gray-800 hover:bg-gray-700 text-gray-400 font-black rounded-2xl transition-all uppercase text-[10px] tracking-[0.2em]">CANCELAR</button>
                    <button type="submit" id="submitBtn" class="px-12 py-5 bg-red-600 hover:bg-red-700 text-white font-black rounded-2xl shadow-2xl shadow-red-600/30 transition-all uppercase text-[10px] tracking-[0.2em] flex items-center transform hover:scale-105 active:scale-95">
                        <span id="btnText">GUARDAR PELÍCULA</span>
                        <i class="fas fa-check-circle ml-3"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        // Definiciones Críticas
        const API_URL = "{{ url('/admin/api') }}";
        const CSRF_ELEMENT = document.querySelector('meta[name="csrf-token"]');
        const CSRF_TOKEN = CSRF_ELEMENT ? CSRF_ELEMENT.getAttribute('content') : document.querySelector('input[name="_token"]').value;

        let currentPage = 1;

        document.addEventListener('DOMContentLoaded', () => {
            fetchMovies();

            let searchTimeout;
            document.getElementById('searchInput').addEventListener('input', (e) => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => fetchMovies(1, e.target.value), 500);
            });

            // Preview logic
            document.getElementById('contentPoster').addEventListener('input', function() {
                const preview = document.getElementById('posterPreview');
                const placeholder = document.getElementById('posterPlaceholder');
                if(this.value) {
                    preview.src = this.value;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                } else {
                    preview.classList.add('hidden');
                    placeholder.classList.remove('hidden');
                }
            });
        });

        async function fetchMovies(page = 1, search = '') {
            currentPage = page;
            const tableBody = document.getElementById('content-table-body');
            tableBody.innerHTML = '<tr><td colspan="4" class="text-center py-32"><div class="inline-block animate-spin rounded-full h-12 w-12 border-t-2 border-red-600"></div></td></tr>';

            try {
                const response = await fetch(`${API_URL}/movies/list?page=${page}&search=${search}`);
                const data = await response.json();
                renderTable(data.data);
                renderPagination(data);
            } catch (error) {
                showToast('error', 'Fallo de conexión al cargar listado.');
            }
        }

        function renderTable(movies) {
            const tableBody = document.getElementById('content-table-body');
            tableBody.innerHTML = '';

            if (movies.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="4" class="text-center py-32 text-gray-600 font-black uppercase tracking-widest italic">Sin registros disponibles</td></tr>';
                return;
            }

            movies.forEach(movie => {
                const genresStr = movie.genres && movie.genres.length > 0
                    ? movie.genres.map(g => `<span class="px-2 py-1 bg-white/5 text-gray-400 text-[8px] font-black rounded-lg uppercase border border-gray-700/50 mr-1 mb-1 inline-block">${g.name}</span>`).join('')
                    : '<span class="text-gray-700 text-[8px] font-black uppercase">S/G</span>';

                const row = `
                    <tr class="hover:bg-white/5 transition-all border-b border-gray-800/30 group">
                        <td class="px-10 py-6">
                            <div class="w-16 h-24 bg-gray-800 rounded-2xl overflow-hidden shadow-2xl group-hover:scale-110 transition-transform duration-500 border border-gray-700/30">
                                <img src="${movie.poster_url || 'https://via.placeholder.com/150x225?text=CineApp'}" class="w-full h-full object-cover">
                            </div>
                        </td>
                        <td class="px-10 py-6">
                            <div class="flex flex-col">
                                <span class="text-white font-black text-base uppercase italic tracking-tighter mb-2">${movie.title}</span>
                                <div class="flex items-center gap-6 text-[10px] font-bold text-gray-500 uppercase tracking-widest">
                                    <span><i class="fas fa-calendar-alt text-red-600 mr-2"></i> ${movie.release_year || 'S/A'}</span>
                                    <span><i class="fas fa-clock text-red-600 mr-2"></i> ${movie.duration ? movie.duration + 'M' : 'S/D'}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-10 py-6 flex flex-wrap max-w-[250px] mt-6">
                            ${genresStr}
                        </td>
                        <td class="px-10 py-6 text-right">
                            <div class="flex justify-end gap-3">
                                <button onclick='editMovie(${JSON.stringify(movie).replace(/'/g, "&#39;")})' class="w-12 h-12 rounded-2xl bg-blue-600/10 text-blue-500 hover:bg-blue-600 hover:text-white transition-all transform active:scale-90">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteMovie(${movie.id})" class="w-12 h-12 rounded-2xl bg-red-600/10 text-red-500 hover:bg-red-600 hover:text-white transition-all transform active:scale-90">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
                tableBody.insertAdjacentHTML('beforeend', row);
            });
        }

        function renderPagination(data) {
            const container = document.getElementById('pagination');
            if (data.last_page <= 1) { container.innerHTML = ''; return; }

            let html = '<div class="flex items-center justify-center gap-3">';
            if (data.current_page > 1) {
                html += `<button onclick="fetchMovies(${data.current_page - 1})" class="w-12 h-12 bg-gray-800 hover:bg-gray-700 text-white rounded-2xl transition-all flex items-center justify-center"><i class="fas fa-chevron-left"></i></button>`;
            }
            html += `<span class="px-8 py-3 bg-gray-900 border border-gray-800 text-[10px] font-black text-gray-400 uppercase tracking-widest rounded-full">${data.current_page} / ${data.last_page}</span>`;
            if (data.current_page < data.last_page) {
                html += `<button onclick="fetchMovies(${data.current_page + 1})" class="w-12 h-12 bg-gray-800 hover:bg-gray-700 text-white rounded-2xl transition-all flex items-center justify-center"><i class="fas fa-chevron-right"></i></button>`;
            }
            html += '</div>';
            container.innerHTML = html;
        }

        function openModal() {
            document.getElementById('contentForm').reset();
            document.getElementById('contentId').value = '';
            document.getElementById('modalTitle').innerText = 'Nueva Película';
            document.getElementById('posterPreview').classList.add('hidden');
            document.getElementById('posterPlaceholder').classList.remove('hidden');
            document.querySelectorAll('.genre-checkbox').forEach(cb => cb.checked = false);

            const modal = document.getElementById('contentModal');
            const container = document.getElementById('modalContainer');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                container.classList.remove('scale-90', 'opacity-0');
                container.classList.add('scale-100', 'opacity-100');
            }, 50);
        }

        function closeModal() {
            const container = document.getElementById('modalContainer');
            container.classList.add('scale-90', 'opacity-0');
            container.classList.remove('scale-100', 'opacity-100');
            setTimeout(() => {
                document.getElementById('contentModal').classList.add('hidden');
                document.getElementById('contentModal').classList.remove('flex');
            }, 300);
        }

        function editMovie(movie) {
            openModal();
            document.getElementById('modalTitle').innerText = 'Editar Registro';
            document.getElementById('contentId').value = movie.id;
            document.getElementById('contentTitle').value = movie.title;
            document.getElementById('contentYear').value = movie.release_year;
            document.getElementById('contentDuration').value = movie.duration;
            document.getElementById('contentDescription').value = movie.description;
            document.getElementById('contentVideo').value = movie.video_url;
            document.getElementById('contentPoster').value = movie.poster_url;

            if(movie.poster_url) {
                const preview = document.getElementById('posterPreview');
                preview.src = movie.poster_url;
                preview.classList.remove('hidden');
                document.getElementById('posterPlaceholder').classList.add('hidden');
            }

            if(movie.genres) {
                const ids = movie.genres.map(g => g.id.toString());
                document.querySelectorAll('.genre-checkbox').forEach(cb => {
                    cb.checked = ids.includes(cb.value);
                });
            }
        }

        // FUNCIÓN CRÍTICA CORREGIDA
        async function handleFormSubmit(event) {
            event.preventDefault(); // Detiene el GET de inmediato

            const form = event.target;
            const btn = document.getElementById('submitBtn');
            const txt = document.getElementById('btnText');

            btn.disabled = true;
            txt.innerText = 'PROCESANDO...';

            try {
                const formData = new FormData(form);
                const id = formData.get('id');
                const data = Object.fromEntries(formData.entries());

                // Obtener géneros seleccionados
                data.genres = Array.from(form.querySelectorAll('.genre-checkbox:checked')).map(cb => cb.value);

                const url = id ? `${API_URL}/movies/${id}` : `${API_URL}/movies`;
                const method = id ? 'PUT' : 'POST';

                console.log('Enviando a:', url, 'Método:', method, 'Datos:', data);

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const res = await response.json();

                if (response.ok) {
                    closeModal();
                    fetchMovies(currentPage);
                    showToast('success', '¡Registro exitoso!');
                } else {
                    console.error('Respuesta de error:', res);
                    if (res.errors) {
                        const firstErr = Object.values(res.errors)[0][0];
                        showToast('error', firstErr);
                    } else {
                        showToast('error', res.debug || res.message || 'Error en servidor');
                    }
                }
            } catch (error) {
                console.error('Error en catch:', error);
                showToast('error', 'Error de conexión total.');
            } finally {
                btn.disabled = false;
                txt.innerText = 'GUARDAR PELÍCULA';
            }
        }

        async function deleteMovie(id) {
            if(!confirm('¿Eliminar definitivamente?')) return;
            try {
                const response = await fetch(`${API_URL}/movies/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': CSRF_TOKEN }
                });
                if (response.ok) {
                    fetchMovies(currentPage);
                    showToast('success', 'Eliminado.');
                }
            } catch (error) { showToast('error', 'Error al borrar.'); }
        }

        function showToast(type, msg) {
            const container = document.getElementById('alert-container');
            const div = document.createElement('div');
            const color = type === 'success' ? 'bg-green-600' : 'bg-red-600';

            div.className = `${color} text-white px-8 py-5 rounded-[24px] shadow-2xl flex items-center gap-4 animate-toast-in border border-white/20`;
            div.innerHTML = `
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'} text-xl"></i>
                <div class="flex-1">
                    <p class="text-[8px] font-black uppercase tracking-[0.2em] opacity-60">${type === 'success' ? 'Éxito' : 'Fallo'}</p>
                    <p class="text-xs font-bold">${msg}</p>
                </div>
            `;
            container.appendChild(div);
            setTimeout(() => {
                div.classList.add('opacity-0', 'translate-x-full');
                setTimeout(() => div.remove(), 500);
            }, 4000);
        }
    </script>
    <style>
        @keyframes toast-in {
            0% { transform: translateX(100%) scale(0.9); opacity: 0; }
            100% { transform: translateX(0) scale(1); opacity: 1; }
        }
        .animate-toast-in { animation: toast-in 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #333; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #ef4444; }
    </style>
    @endpush
</x-admin-layout>
