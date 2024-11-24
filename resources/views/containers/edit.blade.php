<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-4">{{ isset($container) ? 'Modifier' : 'Ajouter' }} un Contenant</h1>
        <form action="{{ isset($container) ? route('containers.update', $container) : route('containers.store') }}" method="POST">
            @csrf
            @if (isset($container))
                @method('PUT')
            @endif
            <div>
                <label for="size" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom</label>
                <input type="text" id="size" name="size" 
                       value="{{ $container->size ?? old('size') }}" 
                       class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" required>
            </div>
            <button type="submit" 
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4">
                {{ isset($container) ? 'Modifier' : 'Ajouter' }}
            </button>
        </form>
    </div>
</x-app-layout>
