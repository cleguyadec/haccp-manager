<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-6">Gestion des Emplacements pour le Lot #{{ $lot->id }}</h1>
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('success') }}
            </div>
         @endif
        <form action="{{ route('lots.locations.update', $lot->id) }}" method="POST">
            @csrf
            <table class="table-auto w-full border-collapse border border-gray-300 dark:border-gray-600 mb-6">
                <thead>
                    <tr class="bg-gray-200 dark:bg-gray-700">
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Emplacement</th>
                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-800 dark:text-gray-200">Stock</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($locations as $location)
                        <tr class="bg-white dark:bg-gray-800">
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">{{ $location->name }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 ">
                                <input type="number" name="locations[{{ $location->id }}][stock]" 
                                       value="{{ $lot->locations->find($location->id)?->pivot->stock ?? 0 }}" 
                                       class="border-gray-300 dark:border-gray-600 rounded-md shadow-sm w-full">
                                <input type="hidden" name="locations[{{ $location->id }}][id]" value="{{ $location->id }}">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Enregistrer
            </button>
        </form>
    </div>
</x-app-layout>
