<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="container mx-auto p-6">
            <h2 class="text-lg font-semibold mb-2 dark:text-gray-100">Résumé des produits</h2>
            <ul class="list-disc list-inside dark:text-gray-100">
                @foreach (\App\Models\Product::take(5)->get() as $product)
                    <li>{{ $product->name }} : {{ $product->stock }} unités en stock</li>
                @endforeach
            </ul>
            <a href="{{ route('products.manage') }}" class="text-blue-500 underline">Voir tous les produits</a>
        </div>
    </div>

    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-6">Tableau de Bord</h1>

        {{-- Lots expirés --}}
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4">Lots Expirés</h2>
            <table class="table-auto w-full border-collapse border border-gray-300 dark:border-gray-600 mb-4">
                <thead>
                    <tr class="bg-gray-200 dark:bg-gray-700">
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Produit</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Lot</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Qté</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Péremption</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($expiredLots as $lot)
                        <tr class="bg-white dark:bg-gray-800">
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">{{ $lot->product->name }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">{{ $lot->id }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">{{ $lot->stock }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">{{ $lot->expiration_date }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">
                                Aucun lot expiré.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Lots expirant ce mois --}}
        <div>
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4">Lots Expirant ce Mois</h2>
            <table class="table-auto w-full border-collapse border border-gray-300 dark:border-gray-600">
                <thead>
                    <tr class="bg-gray-200 dark:bg-gray-700">
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Produit</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Lot</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Qté</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Péremption</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($currentMonthLots as $lot)
                        <tr class="bg-white dark:bg-gray-800">
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">{{ $lot->product->name }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">{{ $lot->id }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">{{ $lot->stock }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">{{ $lot->expiration_date }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">
                                Aucun lot expirant ce mois.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
