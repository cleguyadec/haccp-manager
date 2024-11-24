<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-4">Images du Lot #{{ $lot->id }}</h1>

        {{-- Formulaire pour ajouter des images --}}
        <form action="{{ route('lots.images.store', $lot->id) }}" method="POST" enctype="multipart/form-data" class="mb-6">
            @csrf
            <div>
                <label for="photos" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ajouter des Images</label>
                <input type="file" id="photos" name="photos[]" multiple
                       class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            </div>
            <button type="submit" 
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mt-4">
                Ajouter
            </button>
        </form>

        {{-- Liste des images --}}
        <div class="grid grid-cols-3 gap-4">
            @foreach ($photos as $photo)
                <div class="bg-gray-100 dark:bg-gray-800 p-4 rounded-md">
                    <img src="{{ asset('storage/' . $photo->photo_path) }}" 
                         alt="Image du Lot" 
                         class="rounded-md mb-2 cursor-pointer"
                         @click="openLightbox('{{ asset('storage/' . $photo->photo_path) }}')">
                    <form action="{{ route('lots.images.destroy', $photo->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Supprimer
                        </button>
                    </form>
                </div>
            @endforeach
        </div>

        {{-- Lightbox Modal --}}
        <div x-data="{ open: false, imageUrl: '' }" x-show="open" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75" 
             x-cloak>
            <div class="relative">
                <img :src="imageUrl" alt="Zoomed Image" class="max-w-full max-h-screen rounded-md">
                <button @click="open = false" 
                        class="absolute top-4 right-4 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Fermer
                </button>
            </div>
        </div>

        <script>
            function openLightbox(imageUrl) {
                Alpine.store('lightbox').open = true;
                Alpine.store('lightbox').imageUrl = imageUrl;
            }
        </script>
    </div>
</x-app-layout>
