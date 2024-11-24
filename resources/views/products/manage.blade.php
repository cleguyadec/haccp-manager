<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-4">Gestion des Produits</h1>

        {{-- Bouton pour afficher le formulaire d’ajout --}}
        <button onclick="toggleAddForm()" 
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mb-4">
            Ajouter un produit
        </button>

        {{-- Formulaire pour ajouter un produit (masqué par défaut) --}}
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
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">
                            <button onclick="openEditModal({{ $product }})"
                                    class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                                Modifier
                            </button>
                            
                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Supprimer
                                </button>
                            </form>
                            
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
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Aucun produit trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

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
                            <label for="editName" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom du produit</label>
                            <input type="text" id="editName" name="name" 
                                   class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" required>
                        </div>
                        <div>
                            <label for="editContainerId" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contenant</label>
                            <select id="editContainerId" name="container_id"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" required>
                                @foreach ($containers as $container)
                                    <option value="{{ $container->id }}">{{ $container->size }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="editPrice" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Prix</label>
                            <input type="number" step="0.01" id="editPrice" name="price" 
                                   class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" required>
                        </div>
                        <div>
                            <label for="editStock" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Stock</label>
                            <input type="number" id="editStock" name="stock" value="{{ $product->stock }}" readonly
                                   class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-gray-200 dark:bg-gray-800 text-gray-900 dark:text-gray-100 cursor-not-allowed">
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

        {{-- Pagination --}}
        {{ $products->links() }}
    </div>

    <script>
        function toggleAddForm() {
            console.log('toggleAddForm exécuté');
            const addForm = document.getElementById('addForm');
            addForm.classList.toggle('hidden');
        }

        function openEditModal(product) {
            document.getElementById('editProductId').value = product.id;
            document.getElementById('editName').value = product.name;
            document.getElementById('editContainerId').value = product.container_id;
            document.getElementById('editPrice').value = product.price;
            document.getElementById('editStock').value = product.stock;
            document.getElementById('editForm').action = `/products/${product.id}`;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
