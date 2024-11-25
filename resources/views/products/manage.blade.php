<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-4">Gestion des Produits</h1>

        {{-- Formulaire de recherche --}}
        <div class="mb-4">
            <form action="{{ route('products.manage') }}" method="GET" class="flex items-center space-x-4">
                <input type="text" name="search" value="{{ $search ?? '' }}" 
                       placeholder="Rechercher un produit..."
                       class="border-gray-300 dark:border-gray-600 rounded-md shadow-sm w-1/3 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Rechercher
                </button>
            </form>
        </div>
        
        {{-- Bouton pour afficher le formulaire d’ajout --}}
        <button onclick="toggleAddForm()" 
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mb-4">
            Ajouter un produit
        </button>

        {{-- Formulaire pour ajouter un produit --}}
        <div id="addForm" class="hidden mb-6">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4">Ajouter un Produit</h2>
            <form action="{{ route('products.store') }}" method="POST" class="space-y-4 bg-gray-100 dark:bg-gray-800 p-4 rounded-md">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom du produit</label>
                    <input type="text" id="name" name="name" 
                           class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" required>
                </div>
                <div>
                    <label for="container_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contenant</label>
                    <select id="container_id" name="container_id"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" required>
                        <option value="" disabled selected>Choisir un contenant</option>
                        @foreach ($containers as $container)
                            <option value="{{ $container->id }}">{{ $container->size }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Prix</label>
                    <input type="number" step="0.01" id="price" name="price" 
                           class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" required>
                </div>
                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Stock</label>
                    <input type="number" id="stock" name="stock" value="0"
                           class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                    <textarea id="description" name="description" rows="3"
                              class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">{{ old('description') }}</textarea>
                </div>
                <div class="flex items-center">
                    <!-- Champ caché pour envoyer "0" si la case n'est pas cochée -->
                    <input type="hidden" name="is_sterilized" value="0">
                    <!-- Case à cocher -->
                    <input type="checkbox" id="is_sterilized" name="is_sterilized" value="1"
                           class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600"
                           {{ old('is_sterilized', $product->is_sterilized ?? false) ? 'checked' : '' }}>
                    <label for="is_sterilized" class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Produit Stérilisé</label>
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

        {{-- Liste des produits --}}
        <table class="table-auto w-full border-collapse border border-gray-300 dark:border-gray-600 mb-6">
            <thead>
                <tr class="bg-gray-200 dark:bg-gray-700">
                    <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Nom</th>
                    <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Contenant</th>
                    <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Prix</th>
                    <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Stock</th>
                    <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Description</th>
                    <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Stérilisé</th>
                    <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr class="bg-white dark:bg-gray-800">
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">{{ $product->name }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">{{ $product->container->size }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">{{ $product->price }} €</td>
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">{{ $product->stock }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">{{ $product->description }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">{{ $product->is_sterilized ? 'Oui' : 'Non' }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">
                            <button onclick="openEditModal({{ $product }})" 
                                    class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                                Modifier
                            </button>
                            <form action="{{ route('lots.create', $product->id) }}" method="GET" class="inline-block">
                                <button type="submit" 
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Créer un lot
                                </button>
                            </form>
                            <form action="{{ route('lots.manage') }}" method="GET" class="inline">
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit" 
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Gérer les Lots
                                </button>
                            </form>
                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDeletion(this)" 
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center border border-gray-300 dark:border-gray-600 px-4 py-2">Aucun produit trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        {{ $products->links() }}

        {{-- Modal pour modifier un produit --}}
        <div id="editModal" class="hidden fixed z-10 inset-0 overflow-y-auto bg-gray-900 bg-opacity-50">
            <div class="flex items-center justify-center min-h-screen">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4">Modifier le Produit</h2>
                    <form id="editForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editProductId" name="id">
                        <div>
                            <label for="editName" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom</label>
                            <input type="text" id="editName" name="name" required
                                   class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        </div>
                        <div>
                            <label for="editContainerId" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contenant</label>
                            <select id="editContainerId" name="container_id" required
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                @foreach ($containers as $container)
                                    <option value="{{ $container->id }}">{{ $container->size }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="editPrice" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Prix</label>
                            <input type="number" step="0.01" id="editPrice" name="price" required
                                   class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        </div>
                        <div>
                            <label for="editStock" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Stock</label>
                            <input type="number" id="editStock" name="stock" readonly
                                   class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-gray-200 dark:bg-gray-800 text-gray-900 dark:text-gray-100 cursor-not-allowed">
                        </div>
                        <div>
                            <label for="editDescription" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                            <textarea id="editDescription" name="description" rows="3"
                                      class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"></textarea>
                        </div>
                        <div>
                            <!-- Champ caché pour envoyer "0" si la case n'est pas cochée -->
                            <input type="hidden" name="is_sterilized" value="0">
                            <!-- Case à cocher -->
                            <input type="checkbox" id="editIsSterilized" name="is_sterilized" value="1"
                                   class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600">
                            <label for="editIsSterilized" class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Stérilisé</label>
                        </div>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
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

        function openEditModal(product) {
            document.getElementById('editProductId').value = product.id;
            document.getElementById('editName').value = product.name;
            document.getElementById('editContainerId').value = product.container_id;
            document.getElementById('editPrice').value = product.price;
            document.getElementById('editStock').value = product.stock;
            document.getElementById('editDescription').value = product.description || '';
            document.getElementById('editIsSterilized').checked = product.is_sterilized;
            document.getElementById('editForm').action = `/products/${product.id}`;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function confirmDeletion(button) {
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
            button.closest('form').submit();
        }
    });
}
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</x-app-layout>
