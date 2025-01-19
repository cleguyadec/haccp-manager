<x-guest-layout>
    <div class="w-full mx-auto p-6">
        <!-- Encart d'information -->
        <div class="mb-6 p-4 bg-blue-100 text-blue-800 rounded">
            <p class="text-sm sm:text-base">
                Les stocks sont mis à jour après chaque préparation et après chaque marché. 
            </p>
            <p class="mt-2 text-sm sm:text-base">
                Pour passer commande, vous pouvez me contacter au 
                <span class="font-bold">06 68 12 99 29</span> ou par mail à l'aide du formulaire ci-dessous (je vous rappellerai pour valider la commande).
            </p>
        </div>

        <!-- Formulaire de recherche -->
        <form action="{{ route('products.public') }}" method="GET" class="mb-6">
            <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-4">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Rechercher un produit..."
                       class="w-full sm:w-1/3 border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                <button type="submit" class="w-full sm:w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Rechercher
                </button>
            </div>
        </form>

        <!-- Table responsive -->
        <form id="orderForm">
            <div class="overflow-x-auto">
                <table class="table-auto w-full border-collapse border border-gray-300 dark:border-gray-600 mb-6">
                    <thead>
                        <tr class="bg-gray-200 dark:bg-gray-700">
                            <th class="border border-gray-300 dark:border-gray-600 px-2 sm:px-4 py-2 text-left text-xs sm:text-sm text-gray-800 dark:text-gray-200">Quantité</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-2 sm:px-4 py-2 text-left text-xs sm:text-sm text-gray-800 dark:text-gray-200">Nom</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-2 sm:px-4 py-2 text-left text-xs sm:text-sm text-gray-800 dark:text-gray-200">Contenant</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-2 sm:px-4 py-2 text-left text-xs sm:text-sm text-gray-800 dark:text-gray-200">Description</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-2 sm:px-4 py-2 text-left text-xs sm:text-sm text-gray-800 dark:text-gray-200">Stock</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-2 sm:px-4 py-2 text-left text-xs sm:text-sm text-gray-800 dark:text-gray-200">Stérilisé</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr class="bg-white dark:bg-gray-800">
                                <td class="border border-gray-300 dark:border-gray-600 px-2 sm:px-4 py-2 text-gray-900 dark:text-gray-100">
                                    <input type="number" name="quantities[{{ $product->id }}]" min="0" max="{{ $product->stock }}" 
                                           placeholder="0" class="w-16 sm:w-20 border-gray-300 rounded-md shadow-sm text-center dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100">
                                </td>
                                <td class="border border-gray-300 dark:border-gray-600 px-2 sm:px-4 py-2 text-xs sm:text-sm text-gray-900 dark:text-gray-100">{{ $product->name }}</td>
                                <td class="border border-gray-300 dark:border-gray-600 px-2 sm:px-4 py-2 text-xs sm:text-sm text-gray-900 dark:text-gray-100">{{ $product->container->size ?? 'N/A' }}</td>
                                <td class="border border-gray-300 dark:border-gray-600 px-2 sm:px-4 py-2 text-xs sm:text-sm text-gray-900 dark:text-gray-100">{{ $product->description ?? 'Pas de description' }}</td>
                                <td class="border border-gray-300 dark:border-gray-600 px-2 sm:px-4 py-2 text-xs sm:text-sm text-gray-900 dark:text-gray-100">{{ $product->stock }}</td>
                                <td class="border border-gray-300 dark:border-gray-600 px-2 sm:px-4 py-2 text-xs sm:text-sm text-gray-900 dark:text-gray-100">{{ $product->is_sterilized ? 'Oui' : 'Non' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Aucun produit disponible.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Bouton de commande -->
            <div class="mt-4">
                <button type="button" onclick="openModal()" class="w-full sm:w-auto bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Passer commande
                </button>
            </div>
        </form>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $products->appends(['search' => request('search')])->links() }}
        </div>
    </div>

    <!-- Modale pour saisir le numéro de téléphone -->
    <div id="modal" class="fixed inset-0 bg-gray-900 bg-opacity-50 items-center justify-center hidden">
        <div class="w-11/12 sm:w-2/3 lg:w-1/3 bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4">Passer commande</h2>
            <form id="modalForm" onsubmit="submitOrder(event)">
                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Numéro de téléphone</label>
                    <input type="tel" id="phone" name="phone" required
                           class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                </div>
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="closeModal()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Annuler
                    </button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Valider
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            const modal = document.getElementById('modal');
            modal.classList.remove('hidden');
        }

        function closeModal() {
            const modal = document.getElementById('modal');
            modal.classList.add('hidden');
        }

        function submitOrder(event) {
            event.preventDefault();
            const phone = document.getElementById('phone').value;
            const quantities = Array.from(document.querySelectorAll('input[name^="quantities"]'))
                .filter(input => input.value > 0)
                .map(input => {
                    const id = input.name.match(/\d+/)[0];
                    const productName = document.querySelector(`td input[name="quantities[${id}]"]`)
                                          .closest('tr')
                                          .querySelectorAll('td')[1].innerText;
                    const container = document.querySelector(`td input[name="quantities[${id}]"]`)
                                         .closest('tr')
                                         .querySelectorAll('td')[2].innerText;
                    return `${productName} (${container}): ${input.value}`;
                });

            if (quantities.length === 0) {
                alert('Veuillez entrer une quantité pour au moins un produit.');
                return;
            }

            const subject = encodeURIComponent('Commande');
            const body = encodeURIComponent(`Bonjour,\n\nVoici les produits que je souhaite commander :\n- ${quantities.join('\n- ')}\n\nMon numéro de téléphone : ${phone}`);
            const mailto = `mailto:chou.devant@outlook.com?subject=${subject}&body=${body}`;
            window.location.href = mailto;

            closeModal();
        }
    </script>
</x-guest-layout>
