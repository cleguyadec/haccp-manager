<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-4">Gestion des Contenants</h1>

        {{-- Bouton pour afficher le formulaire d’ajout --}}
        <button onclick="toggleAddForm()" 
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mb-4">
            Ajouter un contenant
        </button>

        {{-- Formulaire pour ajouter un contenant (masqué par défaut) --}}
        <div id="addForm" class="hidden mb-6">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4">Ajouter un Contenant</h2>
            <form action="{{ route('containers.store') }}" method="POST" class="space-y-4 bg-gray-100 dark:bg-gray-800 p-4 rounded-md">
                @csrf
                <div>
                    <label for="size" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom</label>
                    <input type="text" id="size" name="size" 
                           class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" required>
                </div>
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Ajouter
                </button>
                <button type="button" onclick="toggleAddForm()" 
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Annuler
                </button>
            </form>
        </div>

        {{-- Liste des contenants --}}
        <table class="table-auto w-full border-collapse border border-gray-300 dark:border-gray-600 mb-6">
            <thead>
                <tr class="bg-gray-200 dark:bg-gray-700">
                    <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Nom</th>
                    <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($containers as $container)
                    <tr class="bg-white dark:bg-gray-800">
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">{{ $container->size }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">
                            <button onclick="openEditModal({{ $container }})"
                                    class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                                Modifier
                            </button>
                            <form action="{{ route('containers.destroy', $container) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="text-center border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Aucun contenant trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Modal pour modifier un contenant --}}
        <div id="editModal" class="hidden fixed z-10 inset-0 overflow-y-auto bg-gray-900 bg-opacity-50">
            <div class="flex items-center justify-center min-h-screen">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4">Modifier le Contenant</h2>
                    <form id="editForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div>
                            <label for="editSize" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom</label>
                            <input type="text" id="editSize" name="size" 
                                   class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" required>
                        </div>
                        <button type="submit" 
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Sauvegarder
                        </button>
                        <button type="button" onclick="closeEditModal()" 
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Annuler
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleAddForm() {
            const addForm = document.getElementById('addForm');
            addForm.classList.toggle('hidden');
        }

        function openEditModal(container) {
            document.getElementById('editSize').value = container.size;
            document.getElementById('editForm').action = `/containers/${container.id}`;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
