<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Registrar Nueva Película') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Formulario -->
                    <form method="POST" action="{{ route('admin.movies.store') }}">
                        @csrf

                        <!-- Título -->
                        <div>
                            <x-input-label for="title" :value="__('Título')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <!-- Descripción -->
                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Descripción')" />
                            <textarea id="description" name="description" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Fila con Año y Duración -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                            <div>
                                <x-input-label for="release_year" :value="__('Año de Lanzamiento')" />
                                <x-text-input id="release_year" class="block mt-1 w-full" type="number" name="release_year" :value="old('release_year')" required />
                                <x-input-error :messages="$errors->get('release_year')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="duration" :value="__('Duración (minutos)')" />
                                <x-text-input id="duration" class="block mt-1 w-full" type="number" name="duration" :value="old('duration')" />
                                <x-input-error :messages="$errors->get('duration')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Fila con URL de Poster y Video -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                            <div>
                                <x-input-label for="poster_url" :value="__('URL del Póster')" />
                                <x-text-input id="poster_url" class="block mt-1 w-full" type="url" name="poster_url" :value="old('poster_url')" required />
                                <x-input-error :messages="$errors->get('poster_url')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="video_url" :value="__('URL del Video')" />
                                <x-text-input id="video_url" class="block mt-1 w-full" type="url" name="video_url" :value="old('video_url')" required />
                                <x-input-error :messages="$errors->get('video_url')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Géneros -->
                        <div class="mt-4">
                            <x-input-label for="genres" :value="__('Géneros (separados por coma)')" />
                            <x-text-input id="genres" class="block mt-1 w-full" type="text" name="genres" :value="old('genres')" placeholder="Ej: Acción, Aventura, Thriller" />
                            <x-input-error :messages="$errors->get('genres')" class="mt-2" />
                        </div>

                        <!-- Contenido Adulto -->
                        <div class="block mt-4">
                            <label for="is_adult" class="inline-flex items-center">
                                <input id="is_adult" type="hidden" name="is_adult" value="0">
                                <input id="_is_adult" type="checkbox" name="is_adult" value="1" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800">
                                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('¿Es contenido para adultos? (+18)') }}</span>
                            </label>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                Cancelar
                            </a>

                            <x-primary-button class="ms-4">
                                {{ __('Registrar Película') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
