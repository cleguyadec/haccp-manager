<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="container mx-auto p-6">
            <h1 class="text-xl font-bold mb-4">Tableau de bord</h1>
            <h2 class="text-lg font-semibold mb-2 dark:text-gray-100">Résumé des produits</h2>
            <ul class="list-disc list-inside dark:text-gray-100">
                @foreach (\App\Models\Product::take(5)->get() as $product)
                    <li>{{ $product->name }} : {{ $product->stock }} unités en stock</li>
                @endforeach
            </ul>
            <a href="{{ route('products.manage') }}" class="text-blue-500 underline">Voir tous les produits</a>
        </div>
    </div>
</x-app-layout>
