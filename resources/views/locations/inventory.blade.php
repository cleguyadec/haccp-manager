<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-6">Inventaire de l'emplacement : {{ $location->name }}</h1>

        <!-- Tableau complet pour écrans moyens et grands -->
        <div class="hidden md:block">
            <table class="table-auto w-full border-collapse border border-gray-300 dark:border-gray-600 mb-6">
                <thead>
                    <tr class="bg-gray-200 dark:bg-gray-700">
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Contenant</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Produit</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Lot</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Date de Péremption</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Quantité</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($subtotals as $container => $data)
                        {{-- Regroupement par contenant --}}
                        <tr class="bg-gray-300 dark:bg-gray-700">
                            <td colspan="6" class="font-bold text-gray-800 dark:text-gray-200 px-4 py-2">
                                Contenant : {{ $container }} (Sous-total : {{ $data['total'] }})
                            </td>
                        </tr>
                        @foreach ($data['products'] as $product => $productData)
                            {{-- Regroupement par produit --}}
                            <tr class="bg-gray-200 dark:bg-gray-800">
                                <td></td>
                                <td colspan="5" class="font-bold text-gray-800 dark:text-gray-200 px-4 py-2">
                                    Produit : {{ $product }} (Sous-total : {{ $productData['total'] }})
                                </td>
                            </tr>
                            @foreach ($productData['lots'] as $lot)
                                {{-- Détails par lot --}}
                                <tr class="bg-white dark:bg-gray-800">
                                    <td></td>
                                    <td></td>
                                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">Lot #{{ $lot->lot_id }}</td>
                                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100 {{ \Carbon\Carbon::parse($lot->expiration_date)->isPast() ? 'text-red-500' : (\Carbon\Carbon::parse($lot->expiration_date)->diffInDays(now()) < 30 ? 'text-red-500' : '') }}">
                                        {{ $lot->expiration_date ? \Carbon\Carbon::parse($lot->expiration_date)->format('d/m/Y') : 'N/A' }}
                                    </td>
                                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">{{ $lot->quantity }}</td>
                                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">
                                        <a href="{{ route('lots.locations.manage', ['lot' => $lot->lot_id]) }}"
                                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                            Gérer les stocks
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Affichage simplifié pour mobile -->
        <div class="md:hidden grid grid-cols-1 gap-4">
            @foreach ($subtotals as $container => $data)
                <div class="bg-gray-100 dark:bg-gray-800 p-4 rounded-md shadow-md">
                    <p class="text-gray-700 dark:text-gray-300 font-bold">Contenant : {{ $container }}</p>
                    <p class="text-gray-700 dark:text-gray-300"><strong>Sous-total :</strong> {{ $data['total'] }}</p>
                    <ul class="mt-2">
                        @foreach ($data['products'] as $product => $productData)
                            <li class="text-gray-700 dark:text-gray-300">
                                <strong>{{ $product }}</strong> : {{ $productData['total'] }}
                            </li>
                            @foreach ($productData['lots'] as $lot)
                                <li class="mt-1">
                                    <span class="text-gray-700 dark:text-gray-300">Lot #{{ $lot->lot_id }}</span>
                                    <span class="text-gray-700 dark:text-gray-300">
                                        ({{ $lot->quantity }})
                                    </span>
                                    <a href="{{ route('lots.locations.manage', ['lot' => $lot->lot_id]) }}"
                                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded inline-block mt-1">
                                        Gérer
                                    </a>
                                </li>
                            @endforeach
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
