<?php

namespace App\Http\Controllers;

use App\Models\Lot;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    // Affiche la page de gestion des emplacements
    public function index()
    {
        $locations = Location::all(); // Récupère tous les emplacements
        return view('locations.manage', compact('locations'));
    }

    // Ajoute un nouvel emplacement
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:locations,name',
        ]);
    
        // Créer le nouvel emplacement
        $location = Location::create([
            'name' => $request->name,
        ]);
    
        // Lier ce nouvel emplacement à tous les lots avec un stock initial de 0
        $lots = Lot::all();
        foreach ($lots as $lot) {
            $lot->locations()->attach($location->id, ['stock' => 0]);
        }
    
        return redirect()->route('locations.manage')->with('success', 'Emplacement créé avec succès et lié à tous les lots.');
    }
    

    public function update(Request $request, Location $location)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:locations,name,' . $location->id,
        ]);

        $location->update(['name' => $request->name]);

        return redirect()->route('locations.manage')->with('success', 'Nom de l\'emplacement mis à jour avec succès.');
    }

    public function destroy(Location $location)
    {
        // Récupérer l'emplacement "Maison"
        $defaultLocation = Location::where('name', 'Maison')->first();
    
        if (!$defaultLocation) {
            return redirect()->route('locations.manage')->with('error', 'L\'emplacement "Maison" n\'existe pas. Impossible de supprimer cet emplacement.');
        }
    
        // Déplacer les lots associés vers "Maison"
        foreach ($location->lots as $lot) {
            $existingStockInMaison = $lot->locations()->where('location_id', $defaultLocation->id)->first()->pivot->stock ?? 0;
    
            $currentStock = $lot->locations()->where('location_id', $location->id)->first()->pivot->stock;
    
            $lot->locations()->updateExistingPivot($defaultLocation->id, ['stock' => $existingStockInMaison + $currentStock]);
    
            // Supprimer l'association entre le lot et l'emplacement à supprimer
            $lot->locations()->detach($location->id);
        }
    
        // Supprimer l'emplacement
        $location->delete();
    
        return redirect()->route('locations.manage')->with('success', 'Emplacement supprimé avec succès. Les lots ont été déplacés vers "Maison".');
    }

    public function inventory(Location $location)
    {
        $inventory = $location->lots()
            ->join('products', 'lots.product_id', '=', 'products.id')
            ->join('containers', 'products.container_id', '=', 'containers.id')
            ->where('lot_location.stock', '>', 0) // Filtrer les stocks > 0
            ->select(
                'containers.size as container',
                'products.name as product',
                'lots.expiration_date as expiration_date',
                'lot_location.stock as quantity',
                'lots.id as lot_id'
            )
            ->orderBy('containers.size') // Trier par contenant
            ->orderBy('products.name')  // Trier par produit
            ->orderBy('lots.expiration_date') // Trier par date de péremption
            ->get();

        // Calcul des sous-totaux par contenant et produit
        $subtotals = $inventory->groupBy('container')->map(function ($containerGroup) {
            return [
                'total' => $containerGroup->sum('quantity'),
                'products' => $containerGroup->groupBy('product')->map(function ($productGroup) {
                    return [
                        'total' => $productGroup->sum('quantity'),
                        'lots' => $productGroup,
                    ];
                }),
            ];
        });

        return view('locations.inventory', compact('location', 'subtotals'));
    }


}

