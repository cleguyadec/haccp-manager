<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="container mx-auto p-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Résumé des produits --}}
            <div class="bg-gray-100 dark:bg-gray-800 p-6 rounded-md">
                <h2 class="text-lg font-semibold mb-2 dark:text-gray-100">Résumé des produits</h2>
                <ul class="list-disc list-inside dark:text-gray-100">
                    @foreach (\App\Models\Product::take(5)->get() as $product)
                        <li>{{ $product->name }} : {{ $product->stock }} unités en stock</li>
                    @endforeach
                </ul>
                <a href="{{ route('products.manage') }}" class="text-blue-500 underline">Voir tous les produits</a>
            </div>
    
            {{-- CA estimé --}}
            <div class="bg-gray-100 dark:bg-gray-800 p-6 rounded-md">
                <h2 class="text-lg font-semibold mb-2 dark:text-gray-100">CA estimé</h2>
                <table class="table-auto w-full border-collapse border border-gray-300 dark:border-gray-600">
                    <thead>
                        <tr class="bg-gray-200 dark:bg-gray-700">
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Type</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Contenant</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Quantité</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">CA (€)</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Bénéfice (€)</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Regroupement Stérilisé --}}
                        @php
                            $sterilizedContainers = \App\Models\Container::all();
                        @endphp
                        @if (\App\Models\Product::where('is_sterilized', true)->sum('stock') > 0)
                            <tr class="bg-gray-300 dark:bg-gray-700">
                                <td colspan="5" class="font-bold text-gray-800 dark:text-gray-200 px-4 py-2">Stérilisé</td>
                            </tr>
                            @foreach ($sterilizedContainers as $container)
                                @php
                                    $sterilizedQuantity = \App\Models\Product::where('is_sterilized', true)->where('container_id', $container->id)->sum('stock');
                                @endphp
                                @if ($sterilizedQuantity > 0)
                                    <tr class="bg-white dark:bg-gray-800">
                                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100"></td>
                                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">{{ $container->size }}</td>
                                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">{{ $sterilizedQuantity }}</td>
                                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">
                                            {{ \App\Models\Product::where('is_sterilized', true)->where('container_id', $container->id)->sum(DB::raw('stock * price')) }} €
                                        </td>
                                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">
                                            {{ \App\Models\Product::where('is_sterilized', true)->where('container_id', $container->id)->sum(DB::raw('stock * (price - raw_material_cost)')) }} €
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
    
                        {{-- Sous-total Stérilisé --}}
                        <tr class="bg-gray-200 dark:bg-gray-700">
                            <td colspan="2" class="font-bold text-gray-800 dark:text-gray-200 px-4 py-2">Total Stérilisé</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">
                                {{ \App\Models\Product::where('is_sterilized', true)->sum('stock') }}
                            </td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">
                                {{ \App\Models\Product::where('is_sterilized', true)->sum(DB::raw('stock * price')) }} €
                            </td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">
                                {{ \App\Models\Product::where('is_sterilized', true)->sum(DB::raw('stock * (price - raw_material_cost)')) }} €
                            </td>
                        </tr>
    
                        {{-- Regroupement Frais --}}
                        @php
                            $freshContainers = \App\Models\Container::all();
                        @endphp
                        @if (\App\Models\Product::where('is_sterilized', false)->sum('stock') > 0)
                            <tr class="bg-gray-300 dark:bg-gray-700">
                                <td colspan="5" class="font-bold text-gray-800 dark:text-gray-200 px-4 py-2">Frais</td>
                            </tr>
                            @foreach ($freshContainers as $container)
                                @php
                                    $freshQuantity = \App\Models\Product::where('is_sterilized', false)->where('container_id', $container->id)->sum('stock');
                                @endphp
                                @if ($freshQuantity > 0)
                                    <tr class="bg-white dark:bg-gray-800">
                                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100"></td>
                                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">{{ $container->size }}</td>
                                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">{{ $freshQuantity }}</td>
                                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">
                                            {{ \App\Models\Product::where('is_sterilized', false)->where('container_id', $container->id)->sum(DB::raw('stock * price')) }} €
                                        </td>
                                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">
                                            {{ \App\Models\Product::where('is_sterilized', false)->where('container_id', $container->id)->sum(DB::raw('stock * (price - raw_material_cost)')) }} €
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
    
                        {{-- Sous-total Frais --}}
                        <tr class="bg-gray-200 dark:bg-gray-700">
                            <td colspan="2" class="font-bold text-gray-800 dark:text-gray-200 px-4 py-2">Total Frais</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">
                                {{ \App\Models\Product::where('is_sterilized', false)->sum('stock') }}
                            </td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">
                                {{ \App\Models\Product::where('is_sterilized', false)->sum(DB::raw('stock * price')) }} €
                            </td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">
                                {{ \App\Models\Product::where('is_sterilized', false)->sum(DB::raw('stock * (price - raw_material_cost)')) }} €
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
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
        <div class="container mx-auto p-6">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4">Nombre de Lots et de Bocaux par Mois</h2>
            <table class="table-auto w-full border-collapse border border-gray-300 dark:border-gray-600">
                <thead>
                    <tr class="bg-gray-200 dark:bg-gray-700">
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Mois</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Nombre de Lots</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Nombre de Bocaux</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($monthlyData as $data)
                        <tr class="bg-white dark:bg-gray-800">
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">
                                {{ $data->year_month }}
                            </td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">
                                {{ $data->lot_count }}
                            </td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">
                                {{ $data->total_jars }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
