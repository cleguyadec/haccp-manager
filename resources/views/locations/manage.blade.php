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
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                            {{-- Vérifier si l'emplacement est "Maison" --}}
                            @if ($location->name === 'Maison')
                                <span class="text-gray-500 dark:text-gray-400">{{ $location->name }}</span>
                            @else
                                {{-- Formulaire pour éditer un emplacement --}}
                                <form action="{{ route('locations.update', $location->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="name" value="{{ $location->name }}" 
                                           class="border-gray-300 dark:border-gray-600 rounded-md shadow-sm w-full">
                                    <button type="submit" 
                                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-2">
                                        Sauvegarder
                                    </button>
                                </form>
                            @endif
                        </td>
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">
                            {{-- Désactiver la suppression pour "Maison" --}}
                            @if ($location->name !== 'Maison')
                            <form action="{{ route('locations.destroy', $location->id) }}" method="POST" class="inline-block delete-location-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmLocationDeletion(this)"
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Supprimer
                                </button>
                            </form>
                        @else
                            <span class="text-gray-500 dark:text-gray-400">Action non disponible</span>
                        @endif
                        
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

<script>
    function confirmLocationDeletion(button) {
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: "Cette action est irréversible.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, supprimer',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            // Soumettre le formulaire parent si l'utilisateur confirme
            button.closest('form').submit();
        }
    });
}
</script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</x-app-layout>
