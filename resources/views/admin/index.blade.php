<x-admin-layout>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-white">Dashboard</h1>
    </div>

    <!-- Grid de Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Card Películas -->
        <div class="bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-700 flex items-center">
            <div class="bg-blue-500 bg-opacity-20 rounded-full w-16 h-16 flex items-center justify-center mr-4">
                <i class="fas fa-film text-3xl text-blue-300"></i>
            </div>
            <div>
                <p class="text-sm text-gray-400">Películas</p>
                <p class="text-3xl font-bold">{{ $moviesCount }}</p>
            </div>
        </div>
        <!-- Card Series -->
        <div class="bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-700 flex items-center">
            <div class="bg-green-500 bg-opacity-20 rounded-full w-16 h-16 flex items-center justify-center mr-4">
                <i class="fas fa-tv text-3xl text-green-300"></i>
            </div>
            <div>
                <p class="text-sm text-gray-400">Series</p>
                <p class="text-3xl font-bold">{{ $seriesCount }}</p>
            </div>
        </div>
        <!-- Card Novelas -->
        <div class="bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-700 flex items-center">
            <div class="bg-yellow-500 bg-opacity-20 rounded-full w-16 h-16 flex items-center justify-center mr-4">
                <i class="fas fa-book-open text-3xl text-yellow-300"></i>
            </div>
            <div>
                <p class="text-sm text-gray-400">Novelas</p>
                <p class="text-3xl font-bold">{{ $novelasCount }}</p>
            </div>
        </div>
        <!-- Card Usuarios -->
        <div class="bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-700 flex items-center">
            <div class="bg-red-500 bg-opacity-20 rounded-full w-16 h-16 flex items-center justify-center mr-4">
                <i class="fas fa-users text-3xl text-red-300"></i>
            </div>
            <div>
                <p class="text-sm text-gray-400">Usuarios</p>
                <p class="text-3xl font-bold">{{ $usersCount }}</p>
            </div>
        </div>
    </div>

    <!-- Últimas Películas Agregadas -->
    <h3 class="text-2xl font-bold mb-4 mt-10">Últimas Películas Agregadas</h3>
    <div class="bg-gray-800 shadow-xl rounded-lg overflow-hidden border border-gray-700">
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b border-gray-700 bg-gray-900 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Título</th>
                        <th class="px-5 py-3 border-b border-gray-700 bg-gray-900 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Año</th>
                        <th class="px-5 py-3 border-b border-gray-700 bg-gray-900 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Adulto</th>
                        <th class="px-5 py-3 border-b border-gray-700 bg-gray-900 text-right text-xs font-semibold text-gray-300 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($latestMovies as $movie)
                    <tr class="hover:bg-gray-700 transition duration-150">
                        <td class="px-5 py-4 text-sm">
                            <p class="text-white whitespace-no-wrap font-medium">{{ $movie->title }}</p>
                        </td>
                        <td class="px-5 py-4 text-sm">
                            <p class="text-gray-300 whitespace-no-wrap">{{ $movie->release_year }}</p>
                        </td>
                        <td class="px-5 py-4 text-sm">
                            @if($movie->is_adult)
                                <span class="relative inline-block px-3 py-1 font-semibold text-red-900 leading-tight">
                                    <span aria-hidden class="absolute inset-0 bg-red-200 opacity-50 rounded-full"></span>
                                    <span class="relative text-red-100 text-xs">Sí</span>
                                </span>
                            @else
                                <span class="relative inline-block px-3 py-1 font-semibold text-green-900 leading-tight">
                                    <span aria-hidden class="absolute inset-0 bg-green-200 opacity-50 rounded-full"></span>
                                    <span class="relative text-green-100 text-xs">No</span>
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-sm text-right">
                            <a href="#" class="text-blue-400 hover:text-blue-300 mr-3 transition">Editar</a>
                            <a href="#" class="text-red-400 hover:text-red-300 transition">Eliminar</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-gray-400">No hay películas recientes.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>
