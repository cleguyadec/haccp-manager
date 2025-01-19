<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-6">
            Gestion des Emplacements pour le Lot #{{ $lot->id }} - {{ $lot->product->full_name }}
        </h1>

        {{-- Message de succès --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Tableau pour écrans moyens et grands -->
        <div class="hidden md:block">
            <form action="{{ route('lots.locations.update', $lot->id) }}" method="POST">
                @csrf
                <table class="table-auto w-full border-collapse border border-gray-300 dark:border-gray-600 mb-6">
                    <thead>
                        <tr class="bg-gray-200 dark:bg-gray-700">
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Emplacement</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Stock</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($locations as $location)
                            <tr class="bg-white dark:bg-gray-800">
                                <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">{{ $location->name }}</td>
                                <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                                    <input type="number" name="locations[{{ $location->id }}][stock]" 
                                           value="{{ $lot->locations->find($location->id)?->pivot->stock ?? 0 }}" 
                                           class="border-gray-300 dark:border-gray-600 rounded-md shadow-sm w-full">
                                </td>
                                <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">
                                    <button type="button" onclick="openModal({{ $location->id }})" 
                                            class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                                        Transférer
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

        <!-- Cartes pour affichage mobile -->
        <div class="md:hidden grid grid-cols-1 gap-4">
            @foreach ($locations as $location)
                <form action="{{ route('lots.locations.update', $lot->id) }}" method="POST" class="bg-gray-100 dark:bg-gray-800 p-4 rounded-md shadow-md">
                    @csrf
                    <p class="text-gray-700 dark:text-gray-300 font-bold">Emplacement : {{ $location->name }}</p>
                    <div class="mt-4">
                        <label for="stock-{{ $location->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Stock</label>
                        <input type="number" id="stock-{{ $location->id }}" name="locations[{{ $location->id }}][stock]" 
                               value="{{ $lot->locations->find($location->id)?->pivot->stock ?? 0 }}" 
                               class="border-gray-300 dark:border-gray-600 rounded-md shadow-sm w-full">
                    </div>
                    <div class="flex justify-between mt-4 space-x-2">
                        <!-- Bouton Transférer -->
                        <button type="button" onclick="openModal({{ $location->id }})" 
                                class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded w-full">
                            Transférer
                        </button>
                        <!-- Bouton Enregistrer -->
                        <button type="submit" 
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full">
                            Enregistrer
                        </button>
                    </div>
                </form>
            @endforeach
        </div>

        <!-- Modal pour transférer le stock -->
        <div id="transferModal" class="hidden fixed z-10 inset-0 overflow-y-auto bg-gray-900 bg-opacity-50">
            <div class="flex items-center justify-center min-h-screen">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-96">
                    <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4">Transférer le Stock</h2>
                    <form id="transferForm" action="{{ route('lots.locations.transfer', $lot->id) }}" method="POST">
                        @csrf
                        <input type="hidden" id="sourceLocationId" name="source_location_id">
                        <select id="destinationLocation" name="destination_location_id" 
                                class="border-gray-300 dark:border-gray-600 rounded-md shadow-sm w-full mb-4" required>
                            <optgroup label="Emplacements liés au lot">
                                @foreach ($lotLocations as $location)
                                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Tous les autres emplacements">
                                @foreach ($locations->diff($lotLocations) as $location)
                                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                        <div class="mb-4">
                            <label for="transferAmount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantité à Transférer</label>
                            <input type="number" id="transferAmount" name="amount" 
                                   class="border-gray-300 dark:border-gray-600 rounded-md shadow-sm w-full" min="1" required>
                        </div>
                        <div class="flex justify-end space-x-4">
                            <button type="button" onclick="closeModal()" 
                                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Annuler
                            </button>
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Transférer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openModal(locationId) {
            document.getElementById('sourceLocationId').value = locationId;
            document.getElementById('transferModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('transferModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
