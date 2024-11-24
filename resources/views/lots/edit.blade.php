<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-4">Éditer le Lot #{{ $lot->id }}</h1>
        <form action="{{ route('lots.update', $lot->id) }}" method="POST" class="space-y-4 bg-gray-100 dark:bg-gray-800 p-4 rounded-md">
            @csrf
            @method('PUT')
            <div>
                <label for="expiration_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date de péremption</label>
                <input type="date" id="expiration_date" name="expiration_date" value="{{ $lot->expiration_date }}"
                       class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            </div>
            <div>
                <label for="stock" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Stock</label>
                <input type="number" id="stock" name="stock" value="{{ $lot->stock }}"
                       class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            </div>
            <button type="submit" 
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Sauvegarder
            </button>
        </form>
    </div>
</x-app-layout>
