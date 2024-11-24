<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-6">Gestion des Emplacements</h1>

        {{-- Messages de succès ou d'erreur --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                {{ session('error') }}
            </div>
        @endif

                {{-- Formulaire pour créer un nouvel emplacement --}}
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4">Créer un Nouvel Emplacement</h2>
                <form action="{{ route('locations.store') }}" method="POST" class="mb-6">
                    @csrf
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom de l'Emplacement</label>
                            <input type="text" id="name" name="name" 
                                   class="border-gray-300 dark:border-gray-600 rounded-md shadow-sm w-full" required>
                        </div>
                    </div>
                    <button type="submit" 
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mt-4">
                        Ajouter
                    </button>
                </form>

        {{-- Liste des emplacements existants --}}
        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4">Liste des Emplacements</h2>
        <table class="table-auto w-full border-collapse border border-gray-300 dark:border-gray-600">
            <thead>
                <tr class="bg-gray-200 dark:bg-gray-700">
                    <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Nom</th>
                    <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($locations as $location)
                    <tr class="bg-white dark:bg-gray-800">
                        {{-- Formulaire pour éditer un emplacement --}}
                        <form action="{{ route('locations.update', $location->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                                <input type="text" name="name" value="{{ $location->name }}" 
                                       class="border-gray-300 dark:border-gray-600 rounded-md shadow-sm w-full">
                            </td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">
                                <button type="submit" 
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Sauvegarder
                                </button>

                                {{-- Bouton pour supprimer un emplacement --}}
                                <form action="{{ route('locations.destroy', $location->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet emplacement ?');" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                        Supprimer
                                    </button>
                                </form>
                            </td>
                        </form>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
