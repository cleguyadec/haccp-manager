<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-4">Gestion des Lots</h1>
        
        {{-- Afficher le produit si filtré --}}
        @if ($product)
            <div class="bg-gray-100 dark:bg-gray-800 p-4 rounded-md mb-4">
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                    Lots pour le produit : {{ $product->name }}
                </h2>
            </div>
        @endif

        {{-- Lien pour effacer le filtre --}}
        @if ($product)
            <a href="{{ route('lots.manage') }}" class="text-blue-500 hover:underline">
                Effacer le filtre
            </a>
        @endif

        {{-- Formulaire de recherche --}}
        <div class="mb-4">
            <form action="{{ route('lots.manage') }}" method="GET" class="flex items-center space-x-4">
                <input type="hidden" name="product_id" value="{{ $product->id ?? '' }}">
                <input type="text" name="search" value="{{ $search ?? '' }}" 
                       placeholder="Rechercher un lot..."
                       class="border-gray-300 dark:border-gray-600 rounded-md shadow-sm w-1/3 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Rechercher
                </button>
            </form>
        </div>

        {{-- Tableau des lots --}}
        <table class="table-auto w-full border-collapse border border-gray-300 dark:border-gray-600 mb-6">
            <thead>
                <tr class="bg-gray-200 dark:bg-gray-700">
                    <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">ID</th>
                    <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Produit</th>
                    <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Date de Production</th>
                    <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Date de Stérilisation</th>
                    <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Date de Péremption</th>
                    <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Stock</th>
                    <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($lots as $lot)
                    <tr class="bg-white dark:bg-gray-800">
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">{{ $lot->id }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">{{ $lot->product->name }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">{{ $lot->production_date ?? 'N/A' }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">{{ $lot->sterilization_date ?? 'N/A' }}</td>
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
                                <button type="button" onclick="confirmDeletion(this)" 
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Supprimer
                                </button>
                            </form>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Liens de pagination --}}
        <div class="mt-4">
            {{ $lots->appends(request()->query())->links() }}
        </div>
    </div>

    {{-- Script pour la confirmation de suppression --}}
    <script>
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
