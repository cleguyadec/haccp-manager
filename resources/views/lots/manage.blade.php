<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-4">Gestion des Lots</h1>
        @if ($product)
            <div class="bg-gray-100 dark:bg-gray-800 p-4 rounded-md mb-4">
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                    Lots pour le produit : {{ $product->name }}
                </h2>
            </div>
        @endif
        <table class="table-auto w-full border-collapse border border-gray-300 dark:border-gray-600 mb-6">
            <thead>
                <tr class="bg-gray-200 dark:bg-gray-700">
                    <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">ID</th>
                    <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Produit</th>
                    <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Date de péremption</th>
                    <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Stock</th>
                    <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($lots as $lot)
                    <tr class="bg-white dark:bg-gray-800">
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">{{ $lot->id }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">{{ $lot->product->name }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">{{ $lot->expiration_date ?? 'N/A' }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">{{ $lot->stock }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">
                            {{-- Bouton pour éditer le lot --}}
                            <form action="{{ route('lots.edit', $lot->id) }}" method="GET" class="inline-block">
                                <button type="submit" 
                                        class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                                    Éditer
                                </button>
                            </form>

                            {{-- Bouton pour gérer les images --}}
                            <form action="{{ route('lots.images.manage', $lot->id) }}" method="GET" class="inline-block">
                                <button type="submit" 
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Gérer les Images
                                </button>
                            </form>

                            {{-- Bouton pour gérer les emplacements --}}
                            <form action="{{ route('lots.locations.manage', $lot->id) }}" method="GET" class="inline-block">
                                <button type="submit" 
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Gérer les Emplacements
                                </button>
                            </form>
                        
                            {{-- Formulaire pour supprimer le lot --}}
                            <form action="{{ route('lots.destroy', $lot->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Supprimer
                                </button>
                            </form>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
