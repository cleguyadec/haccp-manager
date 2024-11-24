<?php

namespace App\Http\Controllers;

use App\Models\Lot;
use App\Models\Product;
use App\Models\Location;
use App\Models\LotPhoto;
use Illuminate\Http\Request;

class LotController extends Controller
{
    public function manage()
    {
        $lots = Lot::with('product')->get();
        return view('lots.manage', compact('lots'));
    }

    public function create(Product $product)
    {
        return view('lots.create', compact('product'));
    }

    public function store(Request $request, Product $product)
    {
        $request->validate([
            'expiration_date' => 'nullable|date',
            'stock' => 'required|integer|min:0',
            'photos.*' => 'image|mimes:jpg,png,jpeg|max:2048',
        ]);
    
        // Crée le lot
        $lot = $product->lots()->create([
            'expiration_date' => $request->expiration_date,
            'stock' => $request->stock,
        ]);
    
        // Associe l'emplacement "maison" avec le stock initial
        $defaultLocation = Location::firstOrCreate(['name' => 'maison']);
        $lot->locations()->attach($defaultLocation->id, ['stock' => $request->stock]);

        // Met à jour le stock du produit
        $product->updateStockFromLots();
    
        // Ajoute les photos si présentes
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('lot_photos', 'public');
                $lot->photos()->create(['photo_path' => $path]);
            }
        }
    
        return redirect()->route('lots.manage')->with('success', 'Lot créé avec succès et stock mis à jour.');
    }

    public function destroy(Lot $lot)
    {
        // Diminue le stock du produit du stock du lot supprimé
        $lot->product->decrement('stock', $lot->stock);
    
        // Supprime le lot
        $lot->delete();
    
        return redirect()->route('lots.manage')->with('success', 'Lot supprimé et stock mis à jour.');
    }

    public function update(Request $request, Lot $lot)
{
    dd($request->all()); // Affiche toutes les données reçues
    // Valider les données
    $request->validate([
        'origin_location_id' => 'required|exists:locations,id',
        'destination_location_id' => 'required|exists:locations,id|different:origin_location_id',
        'stock_amount' => 'required|integer|min:1',
    ]);

    // Récupérer les emplacements concernés
    $origin = $lot->locations->find($request->origin_location_id);
    $destination = $lot->locations->find($request->destination_location_id);

    // Vérifier si l'emplacement d'origine a suffisamment de stock
    if (!$origin || $origin->pivot->stock < $request->stock_amount) {
        return redirect()->back()->with('error', 'Stock insuffisant pour déplacer cette quantité.');
    }

    // Mise à jour des stocks
    // Déduire la quantité de l'emplacement d'origine
    $lot->locations()->updateExistingPivot($request->origin_location_id, [
        'stock' => $origin->pivot->stock - $request->stock_amount,
    ]);

    // Ajouter la quantité à l'emplacement de destination
    if ($destination) {
        $lot->locations()->updateExistingPivot($request->destination_location_id, [
            'stock' => $destination->pivot->stock + $request->stock_amount,
        ]);
    } else {
        // Si l'emplacement de destination n'a pas encore d'association, l'attacher
        $lot->locations()->attach($request->destination_location_id, [
            'stock' => $request->stock_amount,
        ]);
    }

    return redirect()->route('lots.locations.manage', $lot->id)->with('success', 'Stock déplacé avec succès.');
}

    
    

    public function edit(Lot $lot)
    {
        return view('lots.edit', compact('lot'));
    }

    public function dashboard()
    {
        $expiredLots = Lot::with('product')
            ->whereNotNull('expiration_date')
            ->where('expiration_date', '<', now())
            ->get();

        $currentMonthLots = Lot::with('product')
            ->whereNotNull('expiration_date')
            ->whereYear('expiration_date', now()->year)
            ->whereMonth('expiration_date', now()->month)
            ->get();

        return view('dashboard', compact('expiredLots', 'currentMonthLots'));
    }

    public function manageLocations(Lot $lot)
    {
        $locations = Location::all();
        return view('lots.locations', compact('lot', 'locations'));
    }

    public function updateLocations(Request $request, Lot $lot)
    {
        $request->validate([
            'locations.*.id' => 'exists:locations,id',
            'locations.*.stock' => 'integer|min:0',
        ]);

        $locationsData = collect($request->input('locations'))
            ->mapWithKeys(function ($location) {
                return [$location['id'] => ['stock' => $location['stock']]];
            });

        $lot->locations()->sync($locationsData);

        // Synchroniser les stocks
        $lot->updateStockFromLocations();

        return redirect()->route('lots.locations', $lot->id)->with('success', 'Emplacements mis à jour.');
    }

    public function moveStock(Request $request, Lot $lot)
    {
        $request->validate([
            'from_location_id' => 'required|exists:locations,id',
            'to_location_id' => 'required|exists:locations,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $fromLocation = $lot->locations()->find($request->from_location_id);
        $toLocation = $lot->locations()->find($request->to_location_id);

        if (!$fromLocation || $fromLocation->pivot->stock < $request->quantity) {
            return redirect()->back()->with('error', 'Stock insuffisant dans l\'emplacement source.');
        }

        // Déplace la quantité
        $fromLocation->pivot->stock -= $request->quantity;
        $fromLocation->pivot->save();

        if ($toLocation) {
            $toLocation->pivot->stock += $request->quantity;
            $toLocation->pivot->save();
        } else {
            $lot->locations()->attach($request->to_location_id, ['stock' => $request->quantity]);
        }

        // Synchronise le stock du lot
        $lot->updateStockFromLocations();

        return redirect()->back()->with('success', 'Stock déplacé avec succès.');
    }

}
