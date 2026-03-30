<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineApp - Lo mejor en Streaming</title>

    <!-- Tailwind CSS (desde Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        .scroll-hide::-webkit-scrollbar { display: none; }
        .scroll-hide { -ms-overflow-style: none; scrollbar-width: none; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #1f2937; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #4b5563; border-radius: 3px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #6b7280; }

        /* Estilo para los carruseles */
        .slider-container {
            position: relative;
            overflow: hidden;
        }
        .slider-wrapper {
            display: flex;
            transition: transform 0.5s ease-in-out;
            scroll-behavior: smooth;
        }
        .movie-card {
            flex: 0 0 auto;
            width: 160px; /* Móvil */
            margin-right: 1.25rem;
            transition: transform 0.3s ease;
        }
        @media (min-width: 768px) {
            .movie-card {
                width: 200px;
            }
        }
        .movie-card:hover {
            transform: scale(1.05);
            z-index: 10;
        }
    </style>
</head>
<body class="bg-gray-900 text-white font-sans antialiased selection:bg-red-600 selection:text-white">
    <nav class="bg-black/80 backdrop-blur-md p-4 shadow-2xl sticky top-0 z-[100] border-b border-white/10">
        <div class="container mx-auto flex justify-between items-center">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="text-3xl font-black text-red-600 tracking-tighter uppercase flex items-center italic">
                <i class="fas fa-play-circle mr-2 drop-shadow-lg"></i> CineApp
            </a>

            <!-- Menú Desktop -->
            <div class="hidden lg:flex space-x-8 items-center">
                <a href="{{ route('home') }}" class="text-white hover:text-red-500 transition font-bold text-sm uppercase tracking-widest">Inicio</a>
                <a href="#" class="text-gray-400 hover:text-white transition font-bold text-sm uppercase tracking-widest">Películas</a>
                <a href="#" class="text-gray-400 hover:text-white transition font-bold text-sm uppercase tracking-widest">Series</a>
                <a href="#" class="text-gray-400 hover:text-white transition font-bold text-sm uppercase tracking-widest">Novelas</a>
                <a href="#" class="text-gray-400 hover:text-white transition font-bold text-sm uppercase tracking-widest">Animes</a>

                <!-- Dropdown Géneros -->
                <div class="relative group">
                    <button class="flex items-center text-gray-400 hover:text-white transition font-bold text-sm uppercase tracking-widest py-2">
                        Géneros <i class="fas fa-chevron-down ml-2 text-[10px] transition-transform group-hover:rotate-180"></i>
                    </button>
                    <div class="absolute left-0 mt-0 w-56 bg-black/95 backdrop-blur-xl rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.5)] py-4 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform origin-top-left z-[110] border border-white/10 max-h-[400px] overflow-y-auto custom-scrollbar">
                        @foreach($allGenres as $genre)
                            <a href="#" class="block px-6 py-3 text-xs font-bold text-gray-400 hover:bg-red-600 hover:text-white hover:pl-8 transition-all uppercase tracking-widest border-l-4 border-transparent hover:border-white">
                                {{ $genre }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Buscador -->
                <form action="#" method="get" class="flex items-center ml-4">
                    <div class="relative">
                        <input type="text" name="q" class="bg-gray-800/50 text-white rounded-full pl-5 pr-12 py-2.5 focus:outline-none focus:ring-2 focus:ring-red-600 w-48 transition-all focus:w-80 text-xs border border-white/10 placeholder-gray-500" placeholder="¿Qué quieres ver hoy?">
                        <button type="submit" class="absolute right-0 top-0 mt-2 mr-4 text-gray-500 hover:text-white transition">
                            <i class="fas fa-search text-sm"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Usuario / Login -->
            <div class="flex items-center space-x-6">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-white transition font-bold text-xs uppercase tracking-widest">Panel</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-xl text-xs font-black transition shadow-xl shadow-red-600/30 uppercase tracking-widest transform active:scale-95">Salir</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-gray-400 hover:text-white transition font-bold text-xs uppercase tracking-widest">Login</a>
                    <a href="{{ route('register') }}" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-xl text-xs font-black transition shadow-xl shadow-red-600/30 uppercase tracking-widest transform active:scale-95">Únete ahora</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="min-h-screen pb-20">
        <!-- Hero Section (Netflix Style) -->
        @if($featuredMovie)
            <div class="relative w-full h-[85vh] lg:h-[95vh] overflow-hidden mb-12">
                <div class="absolute inset-0">
                    <img src="{{ $featuredMovie->poster_url }}" class="w-full h-full object-cover object-center scale-105 blur-[2px] opacity-40">
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/60 to-transparent"></div>
                    <div class="absolute inset-0 bg-gradient-to-r from-gray-900 via-gray-900/20 to-transparent"></div>
                </div>

                <div class="container mx-auto px-6 lg:px-12 h-full flex items-center relative z-10">
                    <div class="w-full lg:w-3/5">
                        <div class="flex items-center space-x-3 mb-6">
                            <span class="px-3 py-1 bg-red-600 text-white text-[10px] font-black rounded-lg uppercase tracking-widest shadow-lg shadow-red-600/40">Destacado</span>
                            <span class="text-gray-400 font-bold text-xs"><i class="fas fa-calendar-alt mr-1"></i> {{ $featuredMovie->release_year }}</span>
                        </div>
                        <h1 class="text-6xl lg:text-8xl font-black text-white mb-6 leading-[0.9] uppercase tracking-tighter italic drop-shadow-2xl">
                            {{ $featuredMovie->title }}
                        </h1>
                        <p class="text-gray-300 text-lg lg:text-xl mb-10 line-clamp-3 leading-relaxed max-w-2xl font-medium drop-shadow-lg">
                            {{ $featuredMovie->description }}
                        </p>
                        <div class="flex flex-wrap gap-4">
                            <a href="#" class="px-10 py-4 bg-white text-black hover:bg-gray-200 font-black rounded-2xl transition flex items-center shadow-2xl transform hover:scale-105 active:scale-95 uppercase text-sm tracking-widest">
                                <i class="fas fa-play mr-3 text-lg"></i> Reproducir
                            </a>
                            <button class="px-10 py-4 bg-white/10 hover:bg-white/20 text-white font-black rounded-2xl backdrop-blur-xl transition border border-white/10 flex items-center shadow-2xl transform hover:scale-105 active:scale-95 uppercase text-sm tracking-widest">
                                <i class="fas fa-plus mr-3 text-lg"></i> Mi Lista
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Contenido Dinámico por Género -->
        <div class="container mx-auto px-6 lg:px-12 space-y-20 relative z-20 -mt-20">
            @foreach($sections as $section)
                <div class="space-y-8">
                    <div class="flex items-center justify-between">
                        <h2 class="text-3xl font-black text-white italic tracking-tighter uppercase flex items-center">
                            <span class="w-2 h-10 bg-red-600 rounded-full mr-4 shadow-lg shadow-red-600/40"></span>
                            {{ $section['genre']->name }}
                        </h2>
                        <a href="#" class="text-gray-500 hover:text-red-500 font-black text-[10px] uppercase tracking-widest transition-all">Ver todo <i class="fas fa-arrow-right ml-2"></i></a>
                    </div>

                    <!-- Carrusel de Películas -->
                    @if($section['movies']->count() > 0)
                        <div class="space-y-4">
                            <h3 class="text-gray-500 font-black text-[10px] uppercase tracking-[0.3em] ml-6">Películas</h3>
                            <div class="relative group">
                                <div class="flex overflow-x-auto space-x-5 pb-8 scroll-hide scroll-smooth px-6 items-center">
                                    @foreach($section['movies'] as $movie)
                                        <div class="movie-card flex-none group/card">
                                            <a href="#" class="block relative aspect-[2/3] rounded-3xl overflow-hidden shadow-2xl border border-white/5 transition-all">
                                                <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover/card:scale-110">
                                                <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-80"></div>

                                                <!-- Overlay Play -->
                                                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover/card:opacity-100 transition-all duration-300 bg-black/40 backdrop-blur-[2px]">
                                                    <div class="w-14 h-14 bg-red-600 rounded-full flex items-center justify-center text-white transform scale-0 group-hover/card:scale-100 transition-transform duration-500 shadow-2xl shadow-red-600/50">
                                                        <i class="fas fa-play ml-1 text-xl"></i>
                                                    </div>
                                                </div>

                                                <!-- Info Badge -->
                                                <div class="absolute bottom-4 left-4 right-4">
                                                    <h4 class="text-white font-black text-xs uppercase truncate mb-1">{{ $movie->title }}</h4>
                                                    <div class="flex items-center text-[8px] text-gray-400 font-bold uppercase tracking-widest">
                                                        <span>{{ $movie->release_year }}</span>
                                                        <span class="mx-2">•</span>
                                                        <span class="text-yellow-500"><i class="fas fa-star mr-1"></i>{{ number_format($movie->rating_avg, 1) }}</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Carrusel de Series -->
                    @if($section['series']->count() > 0)
                        <div class="space-y-4">
                            <h3 class="text-gray-500 font-black text-[10px] uppercase tracking-[0.3em] ml-6">Series de TV</h3>
                            <div class="relative group">
                                <div class="flex overflow-x-auto space-x-5 pb-8 scroll-hide scroll-smooth px-6 items-center">
                                    @foreach($section['series'] as $serie)
                                        <div class="movie-card flex-none group/card">
                                            <a href="#" class="block relative aspect-[2/3] rounded-3xl overflow-hidden shadow-2xl border border-white/5 transition-all">
                                                <img src="{{ $serie->poster_url }}" alt="{{ $serie->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover/card:scale-110">
                                                <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-80"></div>
                                                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover/card:opacity-100 transition-all duration-300 bg-black/40 backdrop-blur-[2px]">
                                                    <div class="w-14 h-14 bg-blue-600 rounded-full flex items-center justify-center text-white transform scale-0 group-hover/card:scale-100 transition-transform duration-500 shadow-2xl shadow-blue-600/50">
                                                        <i class="fas fa-tv text-xl"></i>
                                                    </div>
                                                </div>
                                                <div class="absolute bottom-4 left-4 right-4">
                                                    <h4 class="text-white font-black text-xs uppercase truncate mb-1">{{ $serie->title }}</h4>
                                                    <p class="text-[8px] text-gray-400 font-bold uppercase tracking-widest">Serie de TV</p>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Carrusel de Animes -->
                    @if($section['animes']->count() > 0)
                        <div class="space-y-4">
                            <h3 class="text-gray-500 font-black text-[10px] uppercase tracking-[0.3em] ml-6">Animes</h3>
                            <div class="relative group">
                                <div class="flex overflow-x-auto space-x-5 pb-8 scroll-hide scroll-smooth px-6 items-center">
                                    @foreach($section['animes'] as $anime)
                                        <div class="movie-card flex-none group/card">
                                            <a href="#" class="block relative aspect-[2/3] rounded-3xl overflow-hidden shadow-2xl border border-white/5 transition-all">
                                                <img src="{{ $anime->poster_url }}" alt="{{ $anime->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover/card:scale-110">
                                                <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-80"></div>
                                                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover/card:opacity-100 transition-all duration-300 bg-black/40 backdrop-blur-[2px]">
                                                    <div class="w-14 h-14 bg-purple-600 rounded-full flex items-center justify-center text-white transform scale-0 group-hover/card:scale-100 transition-transform duration-500 shadow-2xl shadow-purple-600/50">
                                                        <i class="fas fa-bolt text-xl"></i>
                                                    </div>
                                                </div>
                                                <div class="absolute bottom-4 left-4 right-4">
                                                    <h4 class="text-white font-black text-xs uppercase truncate mb-1">{{ $anime->title }}</h4>
                                                    <p class="text-[8px] text-gray-400 font-bold uppercase tracking-widest">Anime Japonés</p>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </main>

    <footer class="bg-black py-20 border-t border-white/5 relative z-20">
        <div class="container mx-auto px-6 lg:px-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-16 mb-16">
                <div class="col-span-1 md:col-span-2 space-y-8">
                    <a href="{{ route('home') }}" class="text-4xl font-black text-red-600 tracking-tighter uppercase flex items-center italic">
                        <i class="fas fa-play-circle mr-2"></i> CineApp
                    </a>
                    <p class="text-gray-500 text-sm leading-relaxed max-w-md font-medium uppercase tracking-tighter">
                        La mejor experiencia cinematográfica desde la comodidad de tu hogar. Disfruta de miles de películas, series, novelas y animes en alta definición sin interrupciones.
                    </p>
                    <div class="flex space-x-6">
                        <a href="#" class="w-12 h-12 bg-gray-900 hover:bg-red-600 text-gray-400 hover:text-white rounded-2xl flex items-center justify-center transition-all border border-white/5 shadow-2xl"><i class="fab fa-facebook-f text-lg"></i></a>
                        <a href="#" class="w-12 h-12 bg-gray-900 hover:bg-red-600 text-gray-400 hover:text-white rounded-2xl flex items-center justify-center transition-all border border-white/5 shadow-2xl"><i class="fab fa-instagram text-lg"></i></a>
                        <a href="#" class="w-12 h-12 bg-gray-900 hover:bg-red-600 text-gray-400 hover:text-white rounded-2xl flex items-center justify-center transition-all border border-white/5 shadow-2xl"><i class="fab fa-twitter text-lg"></i></a>
                        <a href="#" class="w-12 h-12 bg-gray-900 hover:bg-red-600 text-gray-400 hover:text-white rounded-2xl flex items-center justify-center transition-all border border-white/5 shadow-2xl"><i class="fab fa-youtube text-lg"></i></a>
                    </div>
                </div>

                <div class="space-y-6">
                    <h4 class="text-white font-black text-[10px] uppercase tracking-[0.3em]">Explorar</h4>
                    <ul class="space-y-4 text-gray-500 text-xs font-bold uppercase tracking-widest">
                        <li><a href="#" class="hover:text-red-600 transition-colors flex items-center"><i class="fas fa-chevron-right mr-2 text-[8px] text-red-600"></i> Películas</a></li>
                        <li><a href="#" class="hover:text-red-600 transition-colors flex items-center"><i class="fas fa-chevron-right mr-2 text-[8px] text-red-600"></i> Series de TV</a></li>
                        <li><a href="#" class="hover:text-red-600 transition-colors flex items-center"><i class="fas fa-chevron-right mr-2 text-[8px] text-red-600"></i> Animes</a></li>
                        <li><a href="#" class="hover:text-red-600 transition-colors flex items-center"><i class="fas fa-chevron-right mr-2 text-[8px] text-red-600"></i> Novelas</a></li>
                    </ul>
                </div>

                <div class="space-y-6">
                    <h4 class="text-white font-black text-[10px] uppercase tracking-[0.3em]">Soporte</h4>
                    <ul class="space-y-4 text-gray-500 text-xs font-bold uppercase tracking-widest">
                        <li><a href="#" class="hover:text-red-600 transition-colors flex items-center"><i class="fas fa-chevron-right mr-2 text-[8px] text-red-600"></i> Ayuda</a></li>
                        <li><a href="#" class="hover:text-red-600 transition-colors flex items-center"><i class="fas fa-chevron-right mr-2 text-[8px] text-red-600"></i> Términos</a></li>
                        <li><a href="#" class="hover:text-red-600 transition-colors flex items-center"><i class="fas fa-chevron-right mr-2 text-[8px] text-red-600"></i> Privacidad</a></li>
                        <li><a href="#" class="hover:text-red-600 transition-colors flex items-center"><i class="fas fa-chevron-right mr-2 text-[8px] text-red-600"></i> Contacto</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-white/5 pt-10 flex flex-col md:flex-row justify-between items-center gap-6">
                <p class="text-gray-600 text-[10px] font-black uppercase tracking-widest text-center md:text-left">
                    © {{ date('Y') }} CineApp Studios. Todos los derechos reservados.
                </p>
                <div class="flex space-x-8 text-[10px] font-black text-gray-600 uppercase tracking-widest">
                    <a href="#" class="hover:text-white transition-all">Aviso Legal</a>
                    <a href="#" class="hover:text-white transition-all">Cookies</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
