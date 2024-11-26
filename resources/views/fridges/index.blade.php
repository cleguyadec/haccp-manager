<x-app-layout>
    <div class="container mx-auto p-6 bg-white dark:bg-gray-900 dark:text-gray-100 rounded">
        <h1 class="text-2xl font-bold mb-4">Liste des Frigos</h1>

        {{-- Affichage des messages de succès ou d'erreur --}}
        @if (session('success'))
            <div class="bg-green-100 text-green-700 border border-green-400 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 text-red-700 border border-red-400 px-4 py-2 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        {{-- Bouton pour afficher ou masquer le formulaire --}}
        <div x-data="{ open: false }" class="mb-4">
            <button @click="open = !open" 
                    class="bg-blue-500 text-white hover:bg-blue-600 py-2 px-4 rounded">
                <span x-show="!open">Ajouter un Frigo</span>
                <span x-show="open">Masquer le Formulaire</span>
            </button>

            {{-- Formulaire de création de frigo --}}
            <div x-show="open" x-transition class="mt-4">
                <div class="p-4 bg-gray-100 dark:bg-gray-800 rounded">
                    <h2 class="text-lg font-bold mb-4">Ajouter un Frigo</h2>
                    <form action="{{ route('fridges.store') }}" method="POST">
                        @csrf
                        <div>
                            <label for="name" class="block font-medium">Nom</label>
                            <input type="text" name="name" id="name" 
                                   class="w-full border rounded p-2 bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" 
                                   required>
                        </div>
                        <div class="mt-4">
                            <label for="location" class="block font-medium">Emplacement</label>
                            <input type="text" name="location" id="location" 
                                   class="w-full border rounded p-2 bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                        </div>
                        <button type="submit" 
                                class="bg-blue-500 text-white hover:bg-blue-600 py-2 px-4 rounded mt-4">
                            Ajouter
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Liste des frigos --}}
        <div class="mt-4 space-y-4">
            @foreach ($fridges as $fridge)
                <div class="bg-gray-100 dark:bg-gray-800 p-4 rounded flex justify-between items-center">
                    {{-- Détails du frigo --}}
                    <div>
                        <h2 class="text-xl font-bold">{{ $fridge->name }}</h2>
                        <p>Emplacement : {{ $fridge->location }}</p>
                    </div>

                    {{-- Actions sur le frigo --}}
                    <div class="space-x-2">
                        {{-- Bouton pour voir les images --}}
                        <a href="{{ route('fridges.show', $fridge) }}" 
                           class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">
                            Voir les Images
                        </a>

                        {{-- Bouton pour éditer le frigo --}}
                        <a href="{{ route('fridges.edit', $fridge) }}" 
                           class="bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded">
                            Éditer
                        </a>

                        {{-- Bouton pour supprimer un frigo avec SweetAlert2 --}}
                        <button onclick="confirmDelete('{{ route('fridges.destroy', $fridge) }}')" 
                                class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded">
                            Supprimer
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Script SweetAlert2 --}}
    <script>
        function confirmDelete(deleteUrl) {
            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: "Cette action supprimera le frigo de manière définitive.",
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
