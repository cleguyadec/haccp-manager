<x-app-layout>
    <div class="container mx-auto p-6 bg-white dark:bg-gray-900 dark:text-gray-100 rounded">
        <h1 class="text-2xl font-bold mb-4">Modifier le Frigo</h1>

        {{-- Affichage des messages de succès ou d'erreur --}}
        @if (session('success'))
            <div class="bg-green-100 text-green-700 border border-green-400 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 text-red-700 border border-red-400 px-4 py-2 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        {{-- Formulaire pour éditer le frigo --}}
        <div class="p-4 bg-gray-100 dark:bg-gray-800 rounded">
            <form action="{{ route('fridges.update', $fridge->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                {{-- Champ Nom --}}
                <div>
                    <label for="name" class="block font-medium">Nom</label>
                    <input type="text" name="name" id="name" value="{{ $fridge->name }}" 
                           class="w-full border rounded p-2 bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" 
                           required>
                </div>

                {{-- Champ Emplacement --}}
                <div class="mt-4">
                    <label for="location" class="block font-medium">Emplacement</label>
                    <input type="text" name="location" id="location" value="{{ $fridge->location }}" 
                           class="w-full border rounded p-2 bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                </div>

                {{-- Boutons d'action --}}
                <div class="mt-6 flex items-center space-x-4">
                    {{-- Bouton Sauvegarder --}}
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">
                        Sauvegarder
                    </button>

                    {{-- Bouton Annuler --}}
                    <a href="{{ route('fridges.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
