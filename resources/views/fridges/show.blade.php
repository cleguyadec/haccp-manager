<x-app-layout>
    <div class="container mx-auto p-6 bg-white dark:bg-gray-900 dark:text-gray-100 rounded">
        <h1 class="text-2xl font-bold mb-4">Frigo : {{ $fridge->name }}</h1>

        {{-- Formulaire pour télécharger une image --}}
        <form action="{{ route('fridges.upload-temperature', $fridge->id) }}" method="POST" enctype="multipart/form-data" class="bg-gray-100 dark:bg-gray-800 p-4 rounded mb-6">
            @csrf
            <div class="mb-4">
                <label for="image" class="block text-sm font-medium">Télécharger une image</label>
                <input type="file" name="image" id="image" required class="mt-1 block w-full text-gray-800 dark:text-gray-100">
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Envoyer
            </button>
        </form>

        {{-- Table des relevés de température --}}
        <h2 class="text-xl font-bold mt-6">Relevés de Température</h2>
        <table class="w-full mt-4 border-collapse border border-gray-300 dark:border-gray-700">
            <thead>
                <tr class="bg-gray-100 dark:bg-gray-800">
                    <th class="border border-gray-300 dark:border-gray-700 px-4 py-2">Date</th>
                    <th class="border border-gray-300 dark:border-gray-700 px-4 py-2">Image</th>
                    <th class="border border-gray-300 dark:border-gray-700 px-4 py-2">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($fridge->temperatures as $temperature)
                    <tr class="bg-gray-50 dark:bg-gray-800">
                        <td class="border border-gray-300 dark:border-gray-700 px-4 py-2">{{ $temperature->captured_at }}</td>
                        <td class="border border-gray-300 dark:border-gray-700 px-4 py-2">
                            {{-- Lien qui ouvre l'image dans un nouvel onglet --}}
                            <a href="{{ Storage::url($temperature->image_path) }}" target="_blank">
                                <img src="{{ Storage::url($temperature->image_path) }}" 
                                     alt="Image" 
                                     class="w-20 h-20 object-cover cursor-pointer">
                            </a>
                        </td>
                        <td class="border border-gray-300 dark:border-gray-700 px-4 py-2">
                            <button type="button" 
                                    onclick="confirmDelete('{{ route('fridges.upload-temperature.delete', $temperature->id) }}')" 
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Supprimer
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
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
