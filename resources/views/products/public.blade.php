<x-guest-layout>
    <div class="w-full mx-auto p-6">
        <!-- Formulaire de recherche -->
        <form action="{{ route('products.public') }}" method="GET" class="mb-6">
            <div class="flex items-center space-x-4">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Rechercher un produit..."
                       class="border-gray-300 dark:border-gray-600 rounded-md shadow-sm w-1/3 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Rechercher
                </button>
            </div>
        </form>

        <!-- Table responsive -->
        <div class="overflow-x-auto">
            <table class="table-auto w-full border-collapse border border-gray-300 dark:border-gray-600 mb-6">
                <thead>
                    <tr class="bg-gray-200 dark:bg-gray-700">
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left text-gray-800 dark:text-gray-200">Nom</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left text-gray-800 dark:text-gray-200">Contenant</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left text-gray-800 dark:text-gray-200">Description</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left text-gray-800 dark:text-gray-200">Stock</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left text-gray-800 dark:text-gray-200">Stérilisé</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr class="bg-white dark:bg-gray-800">
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">{{ $product->name }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">{{ $product->container->size ?? 'N/A' }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">{{ $product->description ?? 'Pas de description' }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">{{ $product->stock }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">{{ $product->is_sterilized ? 'Oui' : 'Non' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Aucun produit disponible.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $products->appends(['search' => request('search')])->links() }}
        </div>
    </div>
</x-guest-layout>
