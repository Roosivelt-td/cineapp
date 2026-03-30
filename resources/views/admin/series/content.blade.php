<x-admin-layout>
    <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-6">
        <div class="flex items-center gap-6">
            <a href="{{ route('admin.series.index', ['type' => $series->type]) }}" class="w-12 h-12 bg-gray-800 hover:bg-gray-700 text-gray-400 rounded-2xl flex items-center justify-center transition-all border border-gray-700/50 shadow-lg">
                <i class="fas fa-chevron-left"></i>
            </a>
            <div>
                <h1 class="text-4xl font-black text-white uppercase tracking-tighter italic">{{ $series->title }}</h1>
                <p class="text-gray-500 text-[10px] font-black uppercase tracking-[0.3em] mt-1">Gestión de Temporadas y Capítulos</p>
            </div>
        </div>
        <button onclick="createNewSeason()" class="bg-red-600 hover:bg-red-700 text-white font-black py-4 px-10 rounded-2xl shadow-2xl shadow-red-600/40 transition-all duration-300 flex items-center transform hover:-translate-y-1 active:scale-95">
            <i class="fas fa-layer-group mr-3"></i> AGREGAR TEMPORADA
        </button>
    </div>

    <div id="alert-container" class="fixed top-8 right-8 z-[200] space-y-4 w-80"></div>

    <div id="seasons-list" class="space-y-8">
        @forelse($series->seasons->sortBy('number') as $season)
        <div class="bg-gray-900/40 backdrop-blur-3xl rounded-[40px] border border-gray-800/50 shadow-2xl overflow-hidden group" id="season-container-{{ $season->id }}">
            <div class="px-10 py-8 flex justify-between items-center bg-black/30 border-b border-gray-800/50">
                <div class="flex items-center gap-6">
                    <span class="w-14 h-14 bg-red-600 text-white text-xl font-black rounded-3xl flex items-center justify-center shadow-2xl shadow-red-600/40 transform group-hover:rotate-6 transition-transform">
                        {{ $season->number }}
                    </span>
                    <div>
                        <h3 class="text-2xl font-black text-white uppercase italic tracking-tighter">{{ $season->name ?: 'Temporada ' . $season->number }}</h3>
                        <p class="text-gray-500 text-[10px] font-black uppercase tracking-[0.2em]">{{ $season->episodes->count() }} Capítulos Listos</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <button onclick="addEpisode({{ $season->id }})" class="px-8 py-3.5 bg-gray-800 hover:bg-red-600 text-white font-black rounded-2xl transition-all uppercase text-[10px] tracking-widest flex items-center shadow-xl active:scale-95">
                        <i class="fas fa-plus mr-2"></i> Nuevo Capítulo
                    </button>
                    <button onclick="deleteSeason({{ $season->id }})" class="w-12 h-12 bg-red-600/10 text-red-500 hover:bg-red-600 hover:text-white rounded-2xl transition-all flex items-center justify-center border border-red-600/20 active:scale-95">
                        <i class="fas fa-trash-alt text-sm"></i>
                    </button>
                </div>
            </div>

            <div class="p-10">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="episodes-container-{{ $season->id }}">
                    @foreach($season->episodes->sortBy('number') as $episode)
                    <div class="bg-gray-800/30 border border-gray-700/30 rounded-3xl overflow-hidden flex flex-col group/ep hover:border-red-600/50 transition-all shadow-xl hover:-translate-y-1 relative" id="episode-{{ $episode->id }}">
                        <!-- Miniatura del Episodio -->
                        <div class="aspect-video w-full relative overflow-hidden bg-gray-900">
                            <img src="{{ $episode->poster_url ?: 'https://via.placeholder.com/400x225?text=Sin+Miniatura' }}" class="w-full h-full object-cover group-hover/ep:scale-110 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                            <div class="absolute bottom-4 left-6">
                                <span class="px-2 py-1 bg-red-600 text-white font-black text-[8px] uppercase italic tracking-widest rounded shadow-lg">Episodio {{ $episode->number }}</span>
                            </div>
                            <div class="absolute top-4 right-4 flex gap-2 opacity-0 group-hover/ep:opacity-100 transition-opacity">
                                <button onclick='editEpisode({{ $season->id }}, {!! json_encode($episode) !!})' class="w-8 h-8 bg-black/60 backdrop-blur text-white rounded-lg flex items-center justify-center hover:bg-blue-600 transition-all">
                                    <i class="fas fa-edit text-[10px]"></i>
                                </button>
                                <button onclick="deleteEpisode({{ $episode->id }})" class="w-8 h-8 bg-black/60 backdrop-blur text-white rounded-lg flex items-center justify-center hover:bg-red-600 transition-all">
                                    <i class="fas fa-times text-[10px]"></i>
                                </button>
                            </div>
                        </div>

                        <div class="p-6">
                            <h4 class="text-white font-black text-base mb-2 truncate uppercase tracking-tighter group-hover/ep:text-red-500 transition-colors">{{ $episode->title }}</h4>
                            <p class="text-gray-500 text-[10px] line-clamp-2 font-bold uppercase tracking-widest leading-relaxed mb-4">{{ $episode->description ?: 'Sin descripción registrada.' }}</p>

                            <div class="flex items-center justify-between pt-4 border-t border-gray-700/30">
                                <span class="text-[8px] font-black text-gray-600 uppercase tracking-widest"><i class="fas fa-clock text-red-600/40 mr-1"></i> {{ $episode->duration ?: '--' }} MINS</span>
                                <a href="{{ $episode->video_url }}" target="_blank" class="px-3 py-1.5 bg-red-600/10 hover:bg-red-600 text-red-600 hover:text-white text-[8px] font-black rounded uppercase tracking-widest transition-all">
                                    PLAY <i class="fas fa-play-circle ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @empty
        <div class="bg-gray-900/40 border-2 border-dashed border-gray-800 rounded-[40px] py-40 text-center shadow-inner">
            <i class="fas fa-layer-group text-7xl text-gray-800 mb-8 animate-pulse"></i>
            <h3 class="text-gray-600 font-black uppercase tracking-[0.3em] italic text-2xl italic">Sin contenido</h3>
            <p class="text-gray-700 mt-3 font-black uppercase text-[10px] tracking-widest">Inicia la serie agregando su primera temporada</p>
        </div>
        @endforelse
    </div>

    <!-- Modal para Episodios (Actualizado con Póster) -->
    <div id="episodeModal" class="fixed inset-0 bg-black/98 items-center justify-center z-[250] backdrop-blur-2xl hidden p-4 overflow-y-auto">
        <div class="bg-gray-900/95 border border-gray-700/50 rounded-[56px] shadow-[0_0_100px_rgba(0,0,0,0.8)] w-full max-w-5xl transform transition-all duration-500 scale-90 opacity-0" id="episodeModalContainer">
            <div class="px-14 py-10 border-b border-gray-800/50 flex justify-between items-center">
                <h2 id="modalTitle" class="text-4xl font-black text-white uppercase tracking-tighter italic">Capítulo <span class="text-red-600">Editor</span></h2>
                <button onclick="closeEpisodeModal()" class="text-gray-400 hover:text-white transition-all bg-gray-800/50 hover:bg-red-600 w-16 h-16 rounded-[24px] flex items-center justify-center group">
                    <i class="fas fa-times text-2xl group-hover:rotate-90 transition-transform"></i>
                </button>
            </div>
            <form id="episodeForm" class="p-14" onsubmit="handleEpisodeSubmit(event)">
                @csrf
                <input type="hidden" id="episodeId" name="id" value="">
                <input type="hidden" id="episodeSeasonId" name="season_id" value="">

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                    <!-- Preview Póster Episodio -->
                    <div>
                        <label class="block text-gray-500 text-[10px] font-black uppercase tracking-widest mb-4">Miniatura (URL)</label>
                        <input type="text" id="episodePoster" name="poster_url" class="w-full bg-gray-800/40 border border-gray-700/50 text-white rounded-2xl p-4 focus:ring-4 focus:ring-red-600/20 transition-all text-xs mb-4" placeholder="https://...">
                        <div class="aspect-video w-full bg-gray-800/20 rounded-3xl border-2 border-dashed border-gray-700 flex flex-col items-center justify-center overflow-hidden relative shadow-inner">
                            <img id="epPosterPreview" src="" class="w-full h-full object-cover hidden">
                            <i id="epPosterIcon" class="fas fa-image text-4xl text-gray-800"></i>
                        </div>
                    </div>

                    <!-- Datos del Episodio -->
                    <div class="lg:col-span-2 space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <div class="md:col-span-3">
                                <label class="block text-gray-500 text-[10px] font-black uppercase tracking-widest mb-3">Título</label>
                                <input type="text" id="episodeTitle" name="title" class="w-full bg-gray-800/40 border border-gray-700/50 text-white rounded-[24px] p-6 focus:ring-4 focus:ring-red-600/20 transition-all text-xl font-black italic shadow-inner" placeholder="Nombre del capítulo" required>
                            </div>
                            <div>
                                <label class="block text-gray-500 text-[10px] font-black uppercase tracking-widest mb-3">Nº</label>
                                <input type="number" id="episodeNumber" name="number" class="w-full bg-gray-800/40 border border-gray-700/50 text-white rounded-[24px] p-6 focus:ring-4 focus:ring-red-600/20 transition-all font-black text-center text-xl" placeholder="1" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-gray-500 text-[10px] font-black uppercase tracking-widest mb-3">Video URL</label>
                                <input type="text" id="episodeVideo" name="video_url" class="w-full bg-gray-800/40 border border-gray-700/50 text-white rounded-[24px] p-5 focus:ring-4 focus:ring-red-600/20 transition-all text-sm font-bold" placeholder="URL .mp4 o Embed" required>
                            </div>
                            <div>
                                <label class="block text-gray-500 text-[10px] font-black uppercase tracking-widest mb-3">Mins</label>
                                <input type="number" id="episodeDuration" name="duration" class="w-full bg-gray-800/40 border border-gray-700/50 text-white rounded-[24px] p-5 focus:ring-4 focus:ring-red-600/20 transition-all text-center font-bold" placeholder="45">
                            </div>
                        </div>

                        <div>
                            <label class="block text-gray-500 text-[10px] font-black uppercase tracking-widest mb-3">Sinopsis</label>
                            <textarea id="episodeDescription" name="description" rows="3" class="w-full bg-gray-800/40 border border-gray-700/50 text-white rounded-[32px] p-6 focus:ring-4 focus:ring-red-600/20 transition-all text-sm leading-relaxed" placeholder="Resumen..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="mt-12 flex justify-end space-x-6 pt-10 border-t border-gray-800/50">
                    <button type="button" onclick="closeEpisodeModal()" class="px-12 py-6 bg-gray-800 hover:bg-gray-700 text-gray-400 font-black rounded-3xl transition-all uppercase text-[10px] tracking-widest">DESCARTAR</button>
                    <button type="submit" id="episodeSubmitBtn" class="px-14 py-6 bg-red-600 hover:bg-red-700 text-white font-black rounded-3xl shadow-[0_15px_40px_rgba(220,38,38,0.3)] transition-all uppercase text-[10px] tracking-widest flex items-center transform hover:scale-105 active:scale-95">
                        <span id="episodeBtnText">PUBLICAR</span>
                        <i class="fas fa-check-circle ml-3"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        const getCsrf = () => {
            const meta = document.querySelector('meta[name="csrf-token"]');
            return meta ? meta.getAttribute('content') : document.querySelector('input[name="_token"]').value;
        };

        const API_URL = "{{ url('/admin/api') }}";
        const SERIES_ID = "{{ $series->id }}";

        // Preview de póster en tiempo real
        document.getElementById('episodePoster').addEventListener('input', function() {
            const preview = document.getElementById('epPosterPreview');
            const icon = document.getElementById('epPosterIcon');
            if(this.value) {
                preview.src = this.value;
                preview.classList.remove('hidden');
                icon.classList.add('hidden');
            } else {
                preview.classList.add('hidden');
                icon.classList.remove('hidden');
            }
        });

        async function createNewSeason() {
            const num = prompt("Número de Temporada:", 1);
            if (!num) return;
            try {
                const response = await fetch(`${API_URL}/series/${SERIES_ID}/seasons`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': getCsrf(), 'Accept': 'application/json' },
                    body: JSON.stringify({ number: parseInt(num), name: 'Temporada ' + num })
                });
                if (response.ok) { showToast('success', 'Temporada creada!'); setTimeout(() => location.reload(), 800); }
                else { showToast('error', 'Error al crear temporada.'); }
            } catch (e) { showToast('error', 'Error de conexión.'); }
        }

        async function deleteSeason(id) {
            if(!confirm('¿Eliminar temporada?')) return;
            try {
                const response = await fetch(`${API_URL}/seasons/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': getCsrf() } });
                if (response.ok) { document.getElementById('season-container-' + id).remove(); showToast('success', 'Eliminado.'); }
            } catch (e) { showToast('error', 'Error.'); }
        }

        function addEpisode(seasonId) {
            document.getElementById('episodeForm').reset();
            document.getElementById('episodeId').value = '';
            document.getElementById('episodeSeasonId').value = seasonId;
            document.getElementById('epPosterPreview').classList.add('hidden');
            document.getElementById('epPosterIcon').classList.remove('hidden');
            document.getElementById('modalTitle').innerText = 'Nuevo Capítulo';
            openModal();
        }

        function editEpisode(seasonId, ep) {
            document.getElementById('episodeForm').reset();
            document.getElementById('episodeId').value = ep.id;
            document.getElementById('episodeSeasonId').value = seasonId;
            document.getElementById('episodeTitle').value = ep.title;
            document.getElementById('episodeNumber').value = ep.number;
            document.getElementById('episodeVideo').value = ep.video_url;
            document.getElementById('episodePoster').value = ep.poster_url || '';
            document.getElementById('episodeDuration').value = ep.duration;
            document.getElementById('episodeDescription').value = ep.description;

            if(ep.poster_url) {
                document.getElementById('epPosterPreview').src = ep.poster_url;
                document.getElementById('epPosterPreview').classList.remove('hidden');
                document.getElementById('epPosterIcon').classList.add('hidden');
            }

            document.getElementById('modalTitle').innerText = 'Editar Capítulo';
            openModal();
        }

        function openModal() {
            const m = document.getElementById('episodeModal');
            const c = document.getElementById('episodeModalContainer');
            m.classList.remove('hidden'); m.classList.add('flex');
            setTimeout(() => { c.classList.remove('scale-90', 'opacity-0'); c.classList.add('scale-100', 'opacity-100'); }, 50);
        }

        function closeEpisodeModal() {
            const c = document.getElementById('episodeModalContainer');
            c.classList.add('scale-90', 'opacity-0'); c.classList.remove('scale-100', 'opacity-100');
            setTimeout(() => { const m = document.getElementById('episodeModal'); m.classList.add('hidden'); m.classList.remove('flex'); }, 300);
        }

        async function handleEpisodeSubmit(e) {
            e.preventDefault();
            const form = e.target;
            const btn = document.getElementById('episodeSubmitBtn');
            btn.disabled = true;
            document.getElementById('episodeBtnText').innerText = 'PUBLICANDO...';

            const formData = new FormData(form);
            const id = formData.get('id');
            const seasonId = formData.get('season_id');
            const data = Object.fromEntries(formData.entries());

            const url = id ? `${API_URL}/episodes/${id}` : `${API_URL}/seasons/${seasonId}/episodes`;
            const method = id ? 'PUT' : 'POST';

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': getCsrf(), 'Accept': 'application/json' },
                    body: JSON.stringify(data)
                });
                if (response.ok) { showToast('success', 'Éxito!'); setTimeout(() => location.reload(), 800); }
                else { showToast('error', 'Error al guardar.'); }
            } catch (e) { showToast('error', 'Error conexión.'); } finally {
                btn.disabled = false; document.getElementById('episodeBtnText').innerText = 'PUBLICAR';
            }
        }

        async function deleteEpisode(id) {
            if(!confirm('¿Borrar?')) return;
            try {
                const response = await fetch(`${API_URL}/episodes/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': getCsrf() } });
                if (response.ok) { document.getElementById('episode-' + id).remove(); showToast('success', 'Eliminado.'); }
            } catch (e) { showToast('error', 'Error.'); }
        }

        function showToast(type, msg) {
            const container = document.getElementById('alert-container');
            const div = document.createElement('div');
            const color = type === 'success' ? 'bg-green-600' : 'bg-red-600';
            div.className = `${color} text-white px-8 py-6 rounded-[32px] shadow-2xl flex items-center gap-5 animate-toast-in border border-white/20`;
            div.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'} text-2xl"></i><div class="flex-1"><p class="text-[9px] font-black uppercase tracking-[0.3em] opacity-60">${type.toUpperCase()}</p><p class="text-xs font-black uppercase tracking-tighter">${msg}</p></div>`;
            container.appendChild(div);
            setTimeout(() => { div.classList.add('opacity-0', 'translate-x-full'); setTimeout(() => div.remove(), 500); }, 4500);
        }
    </script>
    <style>
        @keyframes toast-in { 0% { transform: translateX(100%) scale(0.9); opacity: 0; } 100% { transform: translateX(0) scale(1); opacity: 1; } }
        .animate-toast-in { animation: toast-in 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #333; border-radius: 10px; }
    </style>
    @endpush
</x-admin-layout>
