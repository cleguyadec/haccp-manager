<?php

namespace App\Http\Controllers;

use App\Models\Lot;
use App\Models\Location;
use Illuminate\Http\Request;

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
}

