<x-app-layout>
    <div class="container">
        <h1>{{ isset($product) ? 'Modifier le produit' : 'Ajouter un produit' }}</h1>
        <form action="{{ isset($product) ? route('products.update', $product) : route('products.store') }}" method="POST">
            @csrf
            @if (isset($product))
                @method('PUT')
            @endif
    
            <div class="mb-3">
                <label for="name" class="form-label">Nom du produit</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $product->name ?? old('name') }}" required>
            </div>
            <div class="mb-3">
                <label for="container_size" class="form-label">Taille du contenant</label>
                <input type="text" class="form-control" id="container_size" name="container_size" value="{{ $product->container ?? old('container') }}" required>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Prix</label>
                <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ $product->price ?? old('price') }}" required>
            </div>
            <div>
                <label for="raw_material_cost" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Coût des matières premières</label>
                <input type="number" step="0.01" id="raw_material_cost" name="raw_material_cost"
                       value="{{ old('raw_material_cost', $product->raw_material_cost) }}"
                       class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" required>
            </div>
            <div class="mb-3">
                <label for="stock" class="form-label">Stock</label>
                <input type="number" class="form-control" id="stock" name="stock" value="{{ $product->stock ?? old('stock') }}" required>
            </div>
            <button type="submit" class="btn btn-primary">{{ isset($product) ? 'Mettre à jour' : 'Ajouter' }}</button>
        </form>
    </div>
    </x-app-layout>
    