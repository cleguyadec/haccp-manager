<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Gestion des Produits</h1>
                {{-- Formulaire pour ajouter un produit --}}
                <h2 class="text-xl font-bold mb-4">Ajouter un Produit</h2>
                <form action="{{ route('products.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nom du produit</label>
                        <input type="text" id="name" name="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>
                    <div>
                        <label for="container_size" class="block text-sm font-medium text-gray-700">Taille du contenant</label>
                        <input type="text" id="container_size" name="container_size"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700">Prix</label>
                        <input type="number" step="0.01" id="price" name="price" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>
                    <div>
                        <label for="stock" class="block text-sm font-medium text-gray-700">Stock</label>
                        <input type="number" id="stock" name="stock" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>
                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Ajouter
                    </button>
                </form>

        {{-- Formulaire de recherche et de filtrage --}}
        <form method="GET" action="{{ route('products.manage') }}" class="flex space-x-4 mb-6">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher un produit"
                   class="border-gray-300 rounded-md shadow-sm w-1/3">
            <select name="filter" class="border-gray-300 rounded-md shadow-sm">
                <option value="">Filtrer par stock</option>
                <option value="10" {{ request('filter') == '10' ? 'selected' : '' }}>10 unités ou plus</option>
                <option value="50" {{ request('filter') == '50' ? 'selected' : '' }}>50 unités ou plus</option>
            </select>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Appliquer
            </button>
        </form>

        {{-- Liste des produits --}}
        <table class="table-auto w-full border-collapse border border-gray-300 mb-6">
            <thead>
                <tr>
                    <th class="border border-gray-300 px-4 py-2">Nom</th>
                    <th class="border border-gray-300 px-4 py-2">Contenant</th>
                    <th class="border border-gray-300 px-4 py-2">Prix</th>
                    <th class="border border-gray-300 px-4 py-2">Stock</th>
                    <th class="border border-gray-300 px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td class="border border-gray-300 px-4 py-2">{{ $product->name }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $product->container_size }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $product->price }} €</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $product->stock }}</td>
                        <td class="border border-gray-300 px-4 py-2">
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
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center border border-gray-300 px-4 py-2">Aucun produit trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        {{ $products->links() }}

        {{-- Modal pour modifier un produit --}}
        <div id="editModal" class="hidden fixed z-10 inset-0 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-bold mb-4">Modifier le Produit</h2>
                    <form id="editForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editProductId" name="id">
                        <div>
                            <label for="editName" class="block text-sm font-medium text-gray-700">Nom du produit</label>
                            <input type="text" id="editName" name="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>
                        <div>
                            <label for="editContainerSize" class="block text-sm font-medium text-gray-700">Taille du contenant</label>
                            <input type="text" id="editContainerSize" name="container_size"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>
                        <div>
                            <label for="editPrice" class="block text-sm font-medium text-gray-700">Prix</label>
                            <input type="number" step="0.01" id="editPrice" name="price" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>
                        <div>
                            <label for="editStock" class="block text-sm font-medium text-gray-700">Stock</label>
                            <input type="number" id="editStock" name="stock" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
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
        function openEditModal(product) {
            document.getElementById('editProductId').value = product.id;
            document.getElementById('editName').value = product.name;
            document.getElementById('editContainerSize').value = product.container_size;
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
