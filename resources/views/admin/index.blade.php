<x-admin-layout>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-white">Panel de Control</h1>
        <div class="text-sm text-gray-400">
            Bienvenido, {{ auth()->user()->name }}
        </div>
    </div>

    <!-- Grid de Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <!-- Card Películas -->
        <a href="{{ route('admin.movies.index') }}" class="bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-700 flex items-center hover:border-blue-500 transition group">
            <div class="bg-blue-500 bg-opacity-20 rounded-full w-16 h-16 flex items-center justify-center mr-4 group-hover:scale-110 transition">
                <i class="fas fa-film text-3xl text-blue-300"></i>
            </div>
            <div>
                <p class="text-sm text-gray-400">Películas</p>
                <p class="text-3xl font-bold text-white">{{ $moviesCount }}</p>
            </div>
        </a>
        <!-- Card Series -->
        <a href="{{ route('admin.series.index') }}" class="bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-700 flex items-center hover:border-green-500 transition group">
            <div class="bg-green-500 bg-opacity-20 rounded-full w-16 h-16 flex items-center justify-center mr-4 group-hover:scale-110 transition">
                <i class="fas fa-tv text-3xl text-green-300"></i>
            </div>
            <div>
                <p class="text-sm text-gray-400">Series</p>
                <p class="text-3xl font-bold text-white">{{ $seriesCount }}</p>
            </div>
        </a>
        <!-- Card Novelas -->
        <a href="{{ route('admin.novelas.index') }}" class="bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-700 flex items-center hover:border-yellow-500 transition group">
            <div class="bg-yellow-500 bg-opacity-20 rounded-full w-16 h-16 flex items-center justify-center mr-4 group-hover:scale-110 transition">
                <i class="fas fa-book-open text-3xl text-yellow-300"></i>
            </div>
            <div>
                <p class="text-sm text-gray-400">Novelas</p>
                <p class="text-3xl font-bold text-white">{{ $novelasCount }}</p>
            </div>
        </a>
        <!-- Card Usuarios -->
        <div class="bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-700 flex items-center">
            <div class="bg-red-500 bg-opacity-20 rounded-full w-16 h-16 flex items-center justify-center mr-4">
                <i class="fas fa-users text-3xl text-red-300"></i>
            </div>
            <div>
                <p class="text-sm text-gray-400">Usuarios</p>
                <p class="text-3xl font-bold text-white">{{ $usersCount }}</p>
            </div>
        </div>
    </div>

    <!-- Acciones Rápidas -->
    <h3 class="text-xl font-bold mb-6 text-gray-300 uppercase tracking-wider">Acciones Rápidas</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <a href="{{ route('admin.movies.index') }}" class="flex items-center p-4 bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg shadow-lg hover:from-blue-700 hover:to-blue-800 transition transform hover:-translate-y-1">
            <i class="fas fa-plus-circle text-2xl mr-3 text-white"></i>
            <span class="font-bold text-white">Gestionar Películas</span>
        </a>
        <a href="{{ route('admin.series.index') }}" class="flex items-center p-4 bg-gradient-to-r from-green-600 to-green-700 rounded-lg shadow-lg hover:from-green-700 hover:to-green-800 transition transform hover:-translate-y-1">
            <i class="fas fa-plus-circle text-2xl mr-3 text-white"></i>
            <span class="font-bold text-white">Gestionar Series</span>
        </a>
        <a href="{{ route('admin.animes.index') }}" class="flex items-center p-4 bg-gradient-to-r from-purple-600 to-purple-700 rounded-lg shadow-lg hover:from-purple-700 hover:to-purple-800 transition transform hover:-translate-y-1">
            <i class="fas fa-plus-circle text-2xl mr-3 text-white"></i>
            <span class="font-bold text-white">Gestionar Animes</span>
        </a>
    </div>

    <!-- Últimas Películas Agregadas -->
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-2xl font-bold text-white">Últimas Películas Agregadas</h3>
        <a href="{{ route('admin.movies.index') }}" class="text-blue-400 hover:text-blue-300 text-sm font-medium">Ver todas <i class="fas fa-arrow-right ml-1"></i></a>
    </div>

    <div class="bg-gray-800 shadow-xl rounded-xl overflow-hidden border border-gray-700">
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-4 border-b border-gray-700 bg-gray-900 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Película</th>
                        <th class="px-5 py-4 border-b border-gray-700 bg-gray-900 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Año</th>
                        <th class="px-5 py-4 border-b border-gray-700 bg-gray-900 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Géneros</th>
                        <th class="px-5 py-4 border-b border-gray-700 bg-gray-900 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($latestMovies as $movie)
                    <tr class="hover:bg-gray-700/50 transition duration-150">
                        <td class="px-5 py-4 text-sm">
                            <div class="flex items-center">
                                <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="w-8 h-12 object-cover rounded mr-3 shadow">
                                <p class="text-white font-medium">{{ $movie->title }}</p>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-sm">
                            <p class="text-gray-300">{{ $movie->release_year }}</p>
                        </td>
                        <td class="px-5 py-4 text-sm">
                            <div class="flex flex-wrap gap-1">
                                @if(is_array($movie->genres))
                                    @foreach(array_slice($movie->genres, 0, 2) as $genre)
                                        <span class="px-2 py-0.5 bg-gray-700 text-gray-300 text-[10px] rounded-full">{{ $genre }}</span>
                                    @endforeach
                                @endif
                            </div>
                        </td>
                        <td class="px-5 py-4 text-sm text-right">
                            <a href="{{ route('admin.movies.index') }}" class="text-blue-400 hover:text-blue-300 transition">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-8 text-gray-500 italic">No hay contenido registrado recientemente.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>
