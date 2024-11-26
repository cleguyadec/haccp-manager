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
                    <a href="{{ asset('storage/' . $photo->photo_path) }}" target="_blank">
                        <img src="{{ asset('storage/' . $photo->photo_path) }}" 
                             alt="Image du Lot" 
                             class="rounded-md mb-2 cursor-pointer">
                    </a>
                    <button onclick="confirmDelete('{{ route('lots.images.destroy', $photo->id) }}')"
                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Supprimer
                    </button>
                </div>
            @endforeach
        </div>
    </div>

    {{-- SweetAlert2 Script --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(deleteUrl) {
            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: "Cette action supprimera définitivement cette image.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = deleteUrl;

                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '{{ csrf_token() }}';
                    form.appendChild(csrfInput);

                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
</x-app-layout>
