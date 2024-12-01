<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-4">Créer un Lot pour {{ $product->name }}</h1>
        <form action="{{ route('lots.store', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-4 bg-gray-100 dark:bg-gray-800 p-4 rounded-md">
            @csrf
            
            {{-- Date de production --}}
            <div>
                <label for="production_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date de Production</label>
                <input type="date" id="production_date" name="production_date" 
                       value="{{ now()->format('Y-m-d') }}" 
                       class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            </div>

            {{-- Date de stérilisation (si applicable) --}}
            @if ($product->is_sterilized)
                <div>
                    <label for="sterilization_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date de Stérilisation</label>
                    <input type="date" id="sterilization_date" name="sterilization_date" 
                           value="{{ now()->format('Y-m-d') }}" 
                           class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                </div>
            @endif

            {{-- Date de péremption --}}
            <div>
                <label for="expiration_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date de Péremption</label>
                <input type="date" id="expiration_date" name="expiration_date" 
                       value="{{ $product->is_sterilized 
                                  ? now()->addMonths(9)->format('Y-m-d') 
                                  : now()->addDays(3)->format('Y-m-d') }}" 
                       class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" readonly>
            </div>

            {{-- Stock --}}
            <div>
                <label for="stock" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Stock</label>
                <input type="number" id="stock" name="stock" value="0"
                       class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" required>
            </div>

            {{-- Photos 
            <div>
                <label for="photos" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Photos</label>
                <input type="file" id="photos" name="photos[]" multiple
                       class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            </div>--}}

            {{-- Bouton d'enregistrement --}}
            <button type="submit" 
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Enregistrer le Lot
            </button>
        </form>
    </div>

    <script>
        // Dynamiquement mettre à jour la date de péremption si les dates de production/stérilisation changent
        document.getElementById('production_date').addEventListener('change', updateExpirationDate);
        document.getElementById('sterilization_date')?.addEventListener('change', updateExpirationDate);

        function updateExpirationDate() {
            const isSterilized = {{ $product->is_sterilized ? 'true' : 'false' }};
            const productionDate = document.getElementById('production_date').value;
            const sterilizationDate = document.getElementById('sterilization_date')?.value;

            let expirationDate = new Date(productionDate);

            if (isSterilized && sterilizationDate) {
                expirationDate = new Date(sterilizationDate);
                expirationDate.setMonth(expirationDate.getMonth() + 9); // Ajoute 9 mois
            } else {
                expirationDate.setDate(expirationDate.getDate() + 3); // Ajoute 3 jours
            }

            document.getElementById('expiration_date').value = expirationDate.toISOString().split('T')[0];
        }
    </script>
</x-app-layout>
