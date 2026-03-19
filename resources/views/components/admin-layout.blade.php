<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - CineApp</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        /* Estilos adicionales si son necesarios */
    </style>
</head>
<body class="bg-gray-900 text-white font-sans antialiased">
    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 min-h-screen p-4 flex flex-col">
            <div class="text-2xl font-bold text-red-600 mb-8 px-2">
                <a href="{{ route('admin.index') }}">Admin Panel</a>
            </div>
            <nav class="flex-1 space-y-1">
                <a href="{{ route('admin.index') }}" class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 {{ request()->routeIs('admin.index') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-tachometer-alt mr-3 w-5 text-center"></i> Dashboard
                </a>

                <div class="pt-4 pb-2 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Contenido</div>

                <a href="{{ route('admin.movies.index') }}" class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 {{ request()->routeIs('admin.movies.*') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-film mr-3 w-5 text-center"></i> Películas
                </a>
                <a href="{{ route('admin.series.index') }}" class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 {{ request()->routeIs('admin.series.*') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-tv mr-3 w-5 text-center"></i> Series
                </a>
                <a href="{{ route('admin.novelas.index') }}" class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 {{ request()->routeIs('admin.novelas.*') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-book-open mr-3 w-5 text-center"></i> Novelas
                </a>
                <a href="{{ route('admin.animes.index') }}" class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 {{ request()->routeIs('admin.animes.*') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-dragon mr-3 w-5 text-center"></i> Animes
                </a>

                <div class="pt-4 pb-2 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Sistema</div>

                <a href="#" class="flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">
                    <i class="fas fa-users mr-3 w-5 text-center"></i> Usuarios
                </a>
            </nav>

            <div class="mt-auto border-t border-gray-700 pt-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center py-2.5 px-4 rounded transition duration-200 hover:bg-red-700 text-red-400 hover:text-white">
                        <i class="fas fa-sign-out-alt mr-3 w-5 text-center"></i> Cerrar Sesión
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-10 overflow-y-auto h-screen">
            <div class="container mx-auto">
                {{ $slot }}
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>
