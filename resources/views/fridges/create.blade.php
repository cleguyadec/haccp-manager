<x-app-layout>
    <div class="container mx-auto p-6 bg-white dark:bg-gray-800 dark:text-gray-200">
        <h1 class="text-2xl font-bold mb-4">Ajouter un Frigo</h1>
        <form action="{{ route('fridges.store') }}" method="POST">
            @csrf
            <div>
                <label for="name" class="block font-medium">Nom</label>
                <input type="text" name="name" id="name" class="w-full border rounded p-2 bg-gray-100 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" required>
            </div>
            <div class="mt-4">
                <label for="location" class="block font-medium">Emplacement</label>
                <input type="text" name="location" id="location" class="w-full border rounded p-2 bg-gray-100 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
            </div>
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded mt-4 dark:bg-blue-600 dark:hover:bg-blue-500">
                Ajouter
            </button>
        </form>
    </div>
</x-app-layout>
