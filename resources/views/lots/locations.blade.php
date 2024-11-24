<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-6">Gestion des Emplacements pour le Lot #{{ $lot->id }}</h1>

        <form action="{{ route('lots.locations.update', $lot->id) }}" method="POST">
            @csrf
            <table class="table-auto w-full border-collapse border border-gray-300 dark:border-gray-600 mb-6">
                <thead>
                    <tr class="bg-gray-200 dark:bg-gray-700">
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Emplacement</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Stock</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Mouvement</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($locations as $location)
                        <tr class="bg-white dark:bg-gray-800">
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">{{ $location->name }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 ">
                                <input type="number" name="locations[{{ $location->id }}][stock]" 
                                       value="{{ $lot->locations->find($location->id)?->pivot->stock ?? 0 }}" 
                                       class="border-gray-300 dark:border-gray-600 rounded-md shadow-sm w-full">
                                <input type="hidden" name="locations[{{ $location->id }}][id]" value="{{ $location->id }}">
                            </td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">
                                <button onclick="openModal({{ $location->id }}, '{{ $location->name }}')" 
                                        class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                                    Déplacer
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                
            </table>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Enregistrer
            </button>
        </form>
    </div>
    <div id="stockModal" class="hidden fixed z-10 inset-0 overflow-y-auto bg-gray-900 bg-opacity-50">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-96">
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4">Déplacer le Stock</h2>
                <form id="stockForm" action="{{ route('lots.locations.update', $lot->id) }}" method="POST">
                    @csrf
                    <input type="hidden" id="originLocationId" name="origin_location_id">
                    <div class="mb-4">
                        <label for="destinationLocation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nouvel Emplacement</label>
                        <select id="destinationLocation" name="destination_location_id" 
                                class="border-gray-300 dark:border-gray-600 rounded-md shadow-sm w-full">
                            @foreach ($locations as $destination)
                                <option value="{{ $destination->id }}">{{ $destination->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="stockAmount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantité à Déplacer</label>
                        <input type="number" id="stockAmount" name="stock_amount" 
                               class="border-gray-300 dark:border-gray-600 rounded-md shadow-sm w-full" min="1">
                    </div>
                    <div class="flex justify-end space-x-4">
                        <button type="button" onclick="closeModal()" 
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Annuler
                        </button>
                        <button type="submit" 
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Déplacer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openModal(locationId, locationName) {
            event.preventDefault(); // Empêche l'action par défaut du bouton
            document.getElementById('originLocationId').value = locationId;
            document.getElementById('stockModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('stockModal').classList.add('hidden');
        }
    </script>
    
</x-app-layout>
