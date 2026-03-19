<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineApp</title>

    <!-- Tailwind CSS (desde Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Alpine.js (ya incluido en app.js de Breeze) -->

    <style>
        .scroll-hide::-webkit-scrollbar { display: none; }
        .scroll-hide { -ms-overflow-style: none; scrollbar-width: none; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #1f2937; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #4b5563; border-radius: 3px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #6b7280; }
    </style>
</head>
<body class="bg-gray-900 text-white font-sans antialiased">
    <nav class="bg-gray-800 p-4 shadow-md sticky top-0 z-50 border-b border-gray-700">
        <div class="container mx-auto flex justify-between items-center">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="text-2xl font-bold text-red-600 tracking-wider uppercase flex items-center">
                <i class="fas fa-play-circle mr-2"></i> CineApp
            </a>

            <!-- Menú Desktop -->
            <div class="hidden md:flex space-x-6 items-center">
                <a href="{{ route('home') }}" class="hover:text-red-500 transition text-sm font-medium">Inicio</a>
                <a href="#" class="hover:text-red-500 transition text-sm font-medium">Películas</a>
                <a href="#" class="hover:text-red-500 transition text-sm font-medium">Series</a>

                <!-- Dropdown Géneros (Dinámico) -->
                <div class="relative group">
                    <button class="flex items-center hover:text-red-500 transition text-sm font-medium focus:outline-none py-2">
                        Géneros <i class="fas fa-chevron-down ml-1 text-xs transition-transform group-hover:rotate-180"></i>
                    </button>
                    <div class="absolute left-0 mt-0 w-48 bg-gray-800 rounded-md shadow-xl py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform origin-top-left z-50 border border-gray-700 max-h-80 overflow-y-auto custom-scrollbar">
                        @foreach($allGenres as $genre)
                            @if($genre)
                            <a href="#" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white hover:pl-6 transition-all border-l-2 border-transparent hover:border-red-600">
                                {{ $genre }}
                            </a>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Buscador -->
                <form action="#" method="get" class="flex items-center ml-2">
                    <div class="relative">
                        <input type="text" name="q" class="bg-gray-900 text-gray-300 rounded-full pl-4 pr-10 py-1.5 focus:outline-none focus:ring-1 focus:ring-red-600 w-40 transition-all focus:w-64 text-sm border border-gray-700 placeholder-gray-500" placeholder="Buscar...">
                        <button type="submit" class="absolute right-0 top-0 mt-1.5 mr-3 text-gray-500 hover:text-white transition">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Usuario / Login -->
            <div class="flex items-center space-x-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-gray-300 hover:text-white transition text-sm font-medium">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-1.5 rounded-full text-sm font-bold transition shadow-lg shadow-red-600/20">Cerrar Sesión</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-gray-300 hover:text-white transition text-sm font-medium">Login</a>
                    <a href="{{ route('register') }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-1.5 rounded-full text-sm font-bold transition shadow-lg shadow-red-600/20">Registro</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="min-h-screen">
        <!-- Hero Section -->
        @if($featuredMovie)
            <div class="relative w-full h-[500px] md:h-[500px] overflow-hidden mb-4 group">
                <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ $featuredMovie->poster_url }}');">
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/40 to-transparent"></div>
                    <div class="absolute inset-0 bg-gradient-to-r from-gray-900 via-gray-900/40 to-transparent"></div>
                </div>
                <div class="container mx-auto px-4 md:px-8 h-full flex items-end pb-16 md:pb-24">
                    <div class="w-full md:w-2/3 lg:w-1/2">
                        <h1 class="text-4xl md:text-6xl font-bold text-white mb-4 leading-tight drop-shadow-lg">{{ $featuredMovie->title }}</h1>
                        <div class="flex items-center space-x-4 text-gray-300 mb-6 text-sm">
                            <span>{{ $featuredMovie->release_year }}</span>
                            <span class="text-yellow-400"><i class="fas fa-star mr-1"></i>{{ number_format($featuredMovie->rating_avg, 1) }}</span>
                            @if($featuredMovie->duration)
                                <span class="w-1 h-1 bg-gray-500 rounded-full"></span>
                                <span>{{ $featuredMovie->duration }} min</span>
                            @endif
                        </div>
                        <p class="text-gray-300 mb-8 line-clamp-3 text-lg leading-relaxed drop-shadow-md">{{ $featuredMovie->description }}</p>
                        <div class="flex space-x-4">
                            <a href="#" class="px-8 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg transition flex items-center shadow-lg shadow-red-600/30 transform hover:-translate-y-1"><i class="fas fa-play mr-2"></i> Ver Ahora</a>
                            <button class="px-8 py-3 bg-white/10 hover:bg-white/20 text-white font-bold rounded-lg backdrop-blur-sm transition border border-white/20 flex items-center"><i class="fas fa-plus mr-2"></i> Mi Lista</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="container mx-auto px-4 md:px-8 py-6">
            <!-- Sección Películas Recientes -->
            <div class="mb-16 relative group/slider">
                <h2 class="text-2xl font-bold text-white border-l-4 border-red-600 pl-4 mb-6">Películas Recientes</h2>
                <div class="relative">
                    <div id="slider-recent-movies" class="flex overflow-x-auto space-x-5 pb-4 scroll-hide scroll-smooth px-1">
                        @foreach($recentMovies as $movie)
                            <div class="flex-none w-[160px] md:w-[180px] group relative rounded-lg overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 bg-gray-800">
                                <a href="#">
                                    <div class="aspect-[2/3] relative w-full">
                                        <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent opacity-80"></div>
                                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10 bg-black/20 backdrop-blur-[1px]">
                                            <div class="w-12 h-12 bg-red-600 rounded-full flex items-center justify-center text-white transform scale-0 group-hover:scale-100 transition-transform duration-300 shadow-lg shadow-red-600/50">
                                                <i class="fas fa-play ml-1"></i>
                                            </div>
                                        </div>
                                        <div class="absolute bottom-0 left-0 w-full p-3 z-20">
                                            <h3 class="text-white font-bold text-sm truncate drop-shadow-md mb-1" title="{{ $movie->title }}">{{ $movie->title }}</h3>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Sección Series Populares -->
            <div class="mb-16 relative group/slider">
                <h2 class="text-2xl font-bold text-white border-l-4 border-red-600 pl-4 mb-6">Series Más Vistas</h2>
                <div class="relative">
                    <div id="slider-popular-series" class="flex overflow-x-auto space-x-5 pb-4 scroll-hide scroll-smooth px-1">
                        @foreach($popularSeries as $series)
                            <div class="flex-none w-[160px] md:w-[180px] group relative rounded-lg overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 bg-gray-800">
                                <a href="#">
                                    <div class="aspect-[2/3] relative w-full">
                                        <img src="{{ $series->poster_url }}" alt="{{ $series->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent opacity-80"></div>
                                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10 bg-black/20 backdrop-blur-[1px]">
                                            <div class="w-12 h-12 bg-red-600 rounded-full flex items-center justify-center text-white transform scale-0 group-hover:scale-100 transition-transform duration-300 shadow-lg shadow-red-600/50">
                                                <i class="fas fa-play ml-1"></i>
                                            </div>
                                        </div>
                                        <div class="absolute bottom-0 left-0 w-full p-3 z-20">
                                            <h3 class="text-white font-bold text-sm truncate drop-shadow-md mb-1" title="{{ $series->title }}">{{ $series->title }}</h3>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-gray-900 border-t border-gray-800 pt-4 pb-8 mt-20">
        <div class="container mx-auto px-4 md:px-8">
            <p class="text-gray-500 text-sm text-center md:text-left mb-4 md:mb-0">
                © {{ date('Y') }} <span class="text-white font-bold">CineApp</span>. Todos los derechos reservados.
            </p>
        </div>
    </footer>
</body>
</html>
