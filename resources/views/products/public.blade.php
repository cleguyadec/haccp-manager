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
        </br>
            <h3>Retrait de commandes :</h3>
            <!-- ✅ Liste en dehors des <p> -->
                <ul class="list-disc list-inside ml-6 mt-2 text-sm sm:text-base">
                    <li>Retrait sur place <strong>(date à valider ensemble)</strong> : 21 rue Duchassein à Puy-Guillaume</li>
                    <li>Vente sur place <strong>(mercredi matin uniquement de 11h à 12h)</strong> : 21 rue Duchassein à Puy-Guillaume</li>
                    <li>Marché de Riom le samedi matin des semaines impaires</li>
                    <li>AMAP de Limon le mardi soir des semaines impaires</li>
                    <li>Livraison (+4€) : Cébazat, marché de Vichy, à domicile après validation</li>
                </ul>
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
                                    <input type="number" name="quantities[{{ $product->id }}]" min="0" max="{{ $product->stock_maison }}" 
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
                                    <strong>Stock :</strong> {{ $product->stock_maison }}<br>
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

    </div>

    <!-- Modale pour saisir le numéro de téléphone et afficher la commande -->
    <div id="modal" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 overflow-y-auto hidden">
        <div class="min-h-screen flex items-start justify-center py-10 px-4">
            <div class="w-full max-w-xl bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 overflow-y-auto max-h-[90vh]">
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4">Passer commande</h2>
                <form id="modalForm" onsubmit="submitOrder(event)">
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom et prénom</label>
                        <input type="text" id="name" name="name" required
                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    </div>
                    
                    <div class="mb-4">
                        <label for="pickup_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date de retrait souhaitée</label>
                        <input type="date" id="pickup_date" name="pickup_date"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    </div>
                    
                    <div class="mb-4">
                        <label for="pickup_location" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Lieu de retrait</label>
                        <select id="pickup_location" name="pickup_location" required
                                class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            <option value="">-- Sélectionner --</option>
                            <option>Retrait sur place</option>
                            <option>Vente sur place</option>
                            <option>Marché de Riom</option>
                            <option>AMAP de Limons</option>
                            <option>Livraison</option>
                        </select>   
                    </div>
                    
                    <div class="mb-4">
                        <label for="payment_mode" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mode de paiement</label>
                        <select id="payment_mode" name="payment_mode" required
                                class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            <option value="">-- Sélectionner --</option>
                            <option>Virement</option>
                            <option>Chèque</option>
                            <option>Carte bancaire (sauf livraison Cébazat/Vichy)</option>
                            <option>Espèces</option>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label for="returned_jars" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre de bocaux consignés rapportés</label>
                        <input type="number" id="returned_jars" name="returned_jars" value="0" min="0"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    </div>
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
                        <button type="button" onclick="downloadPDF()" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded">
                            Télécharger en PDF
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        body.modal-open {
            overflow: hidden;
        }
    </style>
    <script>
        let currentOrderId = "";

        function openModal() {
            const phone = document.getElementById('phone').value;
            const name = document.getElementById('name').value;
            const pickupDate = document.getElementById('pickup_date').value;
            const pickupLocation = document.getElementById('pickup_location').value;
            const paymentMode = document.getElementById('payment_mode').value;
            const returnedJars = parseInt(document.getElementById('returned_jars').value) || 0;

            let total = 0;
            const quantities = Array.from(document.querySelectorAll('input[name^="quantities"]'))
                .filter(input => input.value > 0)
                .map(input => {
                    const id = input.name.match(/\d+/)[0];
                    const productRow = document.querySelector(`td input[name="quantities[${id}]"]`).closest('tr');
                    const productName = productRow.querySelectorAll('td')[1].innerText;
                    const container = productRow.querySelectorAll('td')[2].innerText;
                    const price = parseFloat(productRow.querySelectorAll('td')[3].innerText.replace('€', '').trim()) || 0;
                    const quantity = parseInt(input.value);

                    PriceConsigne = price+2;
                    total += PriceConsigne * quantity;
                    return `${productName} (${container}) ${PriceConsigne} € × ${quantity} → ${PriceConsigne * quantity} €`;
                });

            if (quantities.length === 0) {
                alert('Veuillez entrer une quantité pour au moins un produit.');
                return;
            }

            const consigneDiscount = returnedJars * 2;
            const totalFinal = (total - consigneDiscount).toFixed(2);
            let livraisonSup = 0;
            if (pickupLocation === 'Livraison') {
                livraisonSup = 4;
            }
            const totalAvecLivraison = (parseFloat(totalFinal) + livraisonSup).toFixed(2);
           
            const message = `Bonjour ${name},\n\nVoici votre commande n° ${currentOrderId}:\n\n` +
                `📅 Date de retrait souhaitée : ${pickupDate || '[À compléter]'}\n` +
                `📍 Lieu de retrait : ${pickupLocation || '[À compléter]'}\n` +
                `💳 Mode de paiement : ${paymentMode || '[À compléter]'}\n` +
                `♻️ Bocaux consignés rapportés : ${returnedJars}\n\n` +
                `🧺 Produits commandés (2 € de consigne par bocal):\n- ${quantities.join('\n- ')}\n\n` +
                `💰 Total : ${total.toFixed(2)} €\n` +
                `♻️ Réduction consignes : -${consigneDiscount.toFixed(2)} €\n` +
                `🚚 Supplément livraison : +${livraisonSup.toFixed(2)} €\n` +
                `✅ Total final à régler : ${totalAvecLivraison} €\n\n` +
                `📞 Téléphone : ${phone || '[À compléter]'}`;

            document.getElementById('orderSummary').value = message;
            document.body.classList.add('modal-open');
            document.getElementById('modal').classList.remove('hidden');
        }

        function updateOrderSummary() {
            const phone = document.getElementById('phone').value;
            const name = document.getElementById('name').value;
            const pickupDate = document.getElementById('pickup_date').value;
            const pickupLocation = document.getElementById('pickup_location').value;
            const paymentMode = document.getElementById('payment_mode').value;
            const returnedJars = parseInt(document.getElementById('returned_jars').value) || 0;

            let total = 0;
            const quantities = Array.from(document.querySelectorAll('input[name^="quantities"]'))
                .filter(input => input.value > 0)
                .map(input => {
                    const id = input.name.match(/\d+/)[0];
                    const productRow = input.closest('tr');
                    const productName = productRow.querySelectorAll('td')[1].innerText;
                    const container = productRow.querySelectorAll('td')[2].innerText;
                    const price = parseFloat(productRow.querySelectorAll('td')[3].innerText.replace('€', '').trim()) || 0;
                    const quantity = parseInt(input.value);

                    PriceConsigne = price+2;
                    total += PriceConsigne * quantity;
                    return `${productName} (${container}) ${PriceConsigne} € × ${quantity} → ${PriceConsigne * quantity} €`;
                });

            if (quantities.length === 0) {
                document.getElementById('orderSummary').value = "Veuillez sélectionner au moins un produit.";
                return;
            }

            const consigneDiscount = returnedJars * 2;
            const totalFinal = (total - consigneDiscount).toFixed(2);
            let livraisonSup = 0;
            if (pickupLocation === 'Livraison') {
                livraisonSup = 4;
            }
            const totalAvecLivraison = (parseFloat(totalFinal) + livraisonSup).toFixed(2);
            if (!currentOrderId && pickupDate && pickupLocation) {
                currentOrderId = generateOrderId(pickupLocation, pickupDate);
            }

            const message = `Bonjour ${name || '[Nom]'},\n\nVoici votre commande n° ${currentOrderId}:\n\n` +
                `📅 Date de retrait souhaitée : ${pickupDate || '[À compléter]'}\n` +
                `📍 Lieu de retrait : ${pickupLocation || '[À compléter]'}\n` +
                `💳 Mode de paiement : ${paymentMode || '[À compléter]'}\n` +
                `♻️ Bocaux consignés rapportés : ${returnedJars}\n\n` +
                `🧺 Produits commandés (2 € de consigne par bocal):\n- ${quantities.join('\n- ')}\n\n` +
                `💰 Total : ${total.toFixed(2)} €\n` +
                `♻️ Réduction consignes : -${consigneDiscount.toFixed(2)} €\n` +
                `🚚 Supplément livraison : +${livraisonSup.toFixed(2)} €\n` +
                `✅ Total final à régler : ${totalAvecLivraison} €\n\n` +
                `📞 Téléphone : ${phone || '[À compléter]'}`;

            document.getElementById('orderSummary').value = message;
        }

        async function downloadPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            const name = document.getElementById('name').value || '[Nom]';
            const phone = document.getElementById('phone').value || '[Téléphone]';
            const pickupDate = document.getElementById('pickup_date').value || '[Date]';
            const pickupLocation = document.getElementById('pickup_location').value || '[Lieu]';
            const paymentMode = document.getElementById('payment_mode').value || '[Paiement]';
            const returnedJars = parseInt(document.getElementById('returned_jars').value) || 0;

            let totalHT = 0;
            const productRows = [];

            document.querySelectorAll('input[name^="quantities"]').forEach(input => {
                const quantity = parseInt(input.value);
                if (!quantity || quantity <= 0) return;

                const id = input.name.match(/\d+/)[0];
                const row = input.closest('tr');
                const name = row.querySelectorAll('td')[1].innerText.trim();
                const container = row.querySelectorAll('td')[2].innerText.trim();
                const price = parseFloat(row.querySelectorAll('td')[3].innerText.replace('€', '').trim());

                const lineTotal = (price+2) * quantity;
                totalHT += lineTotal;

                productRows.push([name, container, quantity, (price+2).toFixed(2) + " €", lineTotal.toFixed(2) + " €"]);
            });

            const tva = totalHT * 0.055;
            const consigne = returnedJars * 2;
            const totalTTC = totalHT - consigne;
            let livraisonSup = 0;
            if (pickupLocation === 'Livraison') {
                livraisonSup = 4;
            }
            const totalTTCFinal = totalTTC + livraisonSup;

            // === Titre et infos client ===
            const now = new Date().toISOString().slice(0, 10);
            doc.setFont("Helvetica");
            doc.setFontSize(12);
            doc.text("Chou Devant", 10, 10);
            doc.setFontSize(10);
            doc.text(`Résumé de commande n° ${currentOrderId}`, 10, 16);
            doc.text(`Date : ${now}`, 160, 10);

            doc.setFontSize(10);
            doc.text(`Bonjour ${name},`, 10, 30);
            doc.text(`Date de retrait : ${pickupDate}`, 10, 36);
            doc.text(`Lieu de retrait : ${pickupLocation}`, 10, 42);
            doc.text(`Mode de paiement : ${paymentMode}`, 10, 48);
            doc.text(`Bocaux consignés rapportés : ${returnedJars}`, 10, 54);

            // === Tableau de commande ===
            doc.autoTable({
                startY: 60,
                head: [['Produit', 'Contenant', 'Qté', 'PU', 'Total']],
                body: productRows,
                styles: { fontSize: 10 },
                theme: 'grid'
            });

            // === Totaux ===
            let finalY = doc.lastAutoTable.finalY + 10;

            doc.text(`Total HT : ${totalHT.toFixed(2)} €`, 140, finalY);
            doc.text(`dont TVA (5,5%) : ${tva.toFixed(2)} €`, 140, finalY + 6);
            doc.text(`Réduction consigne : -${consigne.toFixed(2)} €`, 140, finalY + 12);
            doc.setFont("Helvetica", "bold");
            doc.text(`Total TTC : ${totalTTC.toFixed(2)} €`, 140, finalY + 20);
            if (livraisonSup > 0) {
                doc.text(`+ Livraison : ${livraisonSup.toFixed(2)} €`, 140, finalY + 26);
            }
            doc.setFont("Helvetica", "bold");
            doc.text(`Total à régler : ${totalTTCFinal.toFixed(2)} €`, 140, finalY + 34);

            doc.setFont("Helvetica", "normal");
            doc.text(`Téléphone : ${phone}`, 10, finalY + 30);

            // === Téléchargement ===
            doc.save(`commande-${currentOrderId}.pdf`);
        }


        // Met à jour dès qu’un champ change
        document.addEventListener('DOMContentLoaded', function () {
            const fields = ['name', 'pickup_date', 'pickup_location', 'payment_mode', 'returned_jars', 'phone'];
            fields.forEach(id => {
                const el = document.getElementById(id);
                if (el) el.addEventListener('input', updateOrderSummary);
            });

            // Quantités
            document.querySelectorAll('input[name^="quantities"]').forEach(input => {
                input.addEventListener('input', updateOrderSummary);
            });
        });

        function generateOrderId(pickupLocation, pickupDate) {
            const slug = pickupLocation
                .normalize("NFD").replace(/[\u0300-\u036f]/g, "") // supprime accents
                .toLowerCase().replace(/[^a-z0-9]/g, ""); // enlève espaces et caractères spéciaux

            const date = pickupDate || new Date().toISOString().slice(0, 10);

            const letters = "ABCDEFGHJKMNPQRSTUVWXYZ"; // pas de I, L, O, Q
            let code = "";
            for (let i = 0; i < 3; i++) {
                code += letters.charAt(Math.floor(Math.random() * letters.length));
            }

            return `${slug}-${date}-${code}`;
        }

            
        function closeModal() {
            const modal = document.getElementById('modal');
            modal.classList.add('hidden');
            document.body.classList.remove('modal-open');
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

        function toggleDetails(productId) 
        {
        const detailsRow = document.getElementById(`details-${productId}`);
        detailsRow.classList.toggle('hidden');
        }
    </script>
</x-guest-layout>
