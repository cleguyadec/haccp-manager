<x-guest-layout>
    <div class="w-full mx-auto p-6">
        <!-- Encart d'information -->
        <div class="mb-6 p-4 bg-blue-100 text-blue-800 rounded">
            <p class="text-sm sm:text-base">
                Les stocks sont mis à jour après chaque préparation et après chaque marché. Vous pouvez retrouver le détail sur les produits en cliquant sur leur nom.
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
                            <th class="border border-gray-300 dark:border-gray-600 px-2 sm:px-4 py-2 text-left text-xs sm:text-sm text-gray-800 dark:text-gray-200">Prix</th>
                            <!-- T<th class="hidden sm:table-cell border border-gray-300 dark:border-gray-600 px-2 sm:px-4 py-2 text-left text-xs sm:text-sm text-gray-800 dark:text-gray-200">Description</th>
                            <th class="hidden sm:table-cell border border-gray-300 dark:border-gray-600 px-2 sm:px-4 py-2 text-left text-xs sm:text-sm text-gray-800 dark:text-gray-200">Stock</th>
                            <th class="hidden sm:table-cell border border-gray-300 dark:border-gray-600 px-2 sm:px-4 py-2 text-left text-xs sm:text-sm text-gray-800 dark:text-gray-200">Stérilisé</th>-->
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr class="bg-white dark:bg-gray-800">
                                <!-- Quantité -->
                                <td class="border border-gray-300 dark:border-gray-600 px-2 sm:px-4 py-2 text-gray-900 dark:text-gray-100">
                                    <input type="number" name="quantities[{{ $product->id }}]" min="0" max="{{ $product->stock }}" 
                                           placeholder="0" class="w-16 sm:w-20 border-gray-300 rounded-md shadow-sm text-center dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100">
                                </td>
                    
                                <!-- Nom (cliquable) -->
                                <td onclick="toggleDetails({{ $product->id }})" class="cursor-pointer border border-gray-300 dark:border-gray-600 px-2 sm:px-4 py-2 text-xs sm:text-sm text-gray-900 dark:text-gray-100 font-bold underline">
                                    {{ $product->name }}
                                </td>
                    
                                <!-- Contenant -->
                                <td class="border border-gray-300 dark:border-gray-600 px-2 sm:px-4 py-2 text-xs sm:text-sm text-gray-900 dark:text-gray-100">
                                    {{ $product->container->size ?? 'N/A' }}
                                </td>
                    
                                <!-- Prix -->
                                <td class="border border-gray-300 dark:border-gray-600 px-2 sm:px-4 py-2 text-xs sm:text-sm text-gray-900 dark:text-gray-100">
                                    {{ $product->price ?? 'N/A' }} €
                                </td>
                            </tr>
                    
                            <!-- Détails (caché en mobile) -->
                            <tr id="details-{{ $product->id }}" class="hidden sm:table-row bg-gray-100 dark:bg-gray-900">
                                <td colspan="4" class="border border-gray-300 dark:border-gray-600 px-2 sm:px-4 py-2 text-xs sm:text-sm text-gray-900 dark:text-gray-100">
                                    <strong>Description :</strong> {{ $product->description ?? 'Pas de description' }}<br>
                                    <strong>Stock :</strong> {{ $product->stock }}<br>
                                    <strong>Stérilisé :</strong> {{ $product->is_sterilized ? 'Oui' : 'Non' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Aucun produit disponible.</td>
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

    <!-- Modale pour saisir le numéro de téléphone et afficher la commande -->
    <div id="modal" class="fixed inset-0 bg-gray-900 bg-opacity-50 items-center justify-center hidden">
        <div class="w-11/12 sm:w-2/3 lg:w-1/3 bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4">Passer commande</h2>
            <form id="modalForm" onsubmit="submitOrder(event)">
                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Numéro de téléphone</label>
                    <input type="tel" id="phone" name="phone" required
                        class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                </div>
                <div class="mb-4">
                    <label for="orderSummary" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Résumé de la commande</label>
                    <textarea id="orderSummary" rows="5" readonly
                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"></textarea>
                    <button type="button" onclick="copyToClipboard()" class="mt-2 bg-gray-500 hover:bg-gray-700 text-white font-bold py-1 px-3 rounded">
                        Copier la commande
                    </button>
                </div>
                <div class="mb-6 p-4 bg-blue-100 text-blue-800 rounded">
                    <p class="text-sm sm:text-base">
                        Si le bouton "envoyer par mail" ne marche pas, vous pouvez copier-coller et envoyer le mail à chou.devant@outlook.com en ajoutant votre numéro de téléphone. 
                    </p>
                </div>
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="closeModal()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Annuler
                    </button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Envoyer par e-mail
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            const phone = document.getElementById('phone').value;
            const quantities = Array.from(document.querySelectorAll('input[name^="quantities"]'))
                .filter(input => input.value > 0)
                .map(input => {
                    const id = input.name.match(/\d+/)[0];
                    const productRow = document.querySelector(`td input[name="quantities[${id}]"]`).closest('tr');
                    const productName = productRow.querySelectorAll('td')[1].innerText;
                    const container = productRow.querySelectorAll('td')[2].innerText;
                    return `${productName} (${container}): ${input.value}`;
                });
    
            if (quantities.length === 0) {
                alert('Veuillez entrer une quantité pour au moins un produit.');
                return;
            }
    
            const message = `Bonjour,\n\nVoici les produits que je souhaite commander :\n- ${quantities.join('\n- ')}\n\nMon numéro de téléphone : ` + (phone || '[À compléter]');
            
            // Remplissage immédiat du champ texte
            document.getElementById('orderSummary').value = message;
    
            // Affichage de la modale
            const modal = document.getElementById('modal');
            modal.classList.remove('hidden');
        }
    
        function closeModal() {
            const modal = document.getElementById('modal');
            modal.classList.add('hidden');
        }
    
        function copyToClipboard() {
            const textArea = document.getElementById('orderSummary');
            textArea.select();
            document.execCommand('copy');
            alert('Commande copiée dans le presse-papier.');
        }
    
        function submitOrder(event) {
            event.preventDefault();
            const phone = document.getElementById('phone').value.trim();
            let message = document.getElementById('orderSummary').value;
    
            if (phone) {
                message = message.replace('[À compléter]', phone);
            } else {
                alert("Veuillez entrer votre numéro de téléphone.");
                return;
            }
    
            const subject = encodeURIComponent('Commande');
            const body = encodeURIComponent(message);
            const mailto = `mailto:chou.devant@outlook.com?subject=${subject}&body=${body}`;
    
            // Ouverture du client mail
            window.location.href = mailto;
        }
    </script>
</x-guest-layout>
