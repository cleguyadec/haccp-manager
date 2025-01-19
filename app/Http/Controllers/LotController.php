<?php

namespace App\Http\Controllers;

use App\Models\Lot;
use App\Models\Product;
use App\Models\Location;
use App\Models\LotPhoto;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class LotController extends Controller
{
    public function manage(Request $request)
    {
        // Récupérer l'ID du produit et le mot-clé de recherche depuis la requête
        $productId = $request->input('product_id');
        $search = $request->input('search');
    
        // Construire la requête pour récupérer les lots
        $lotsQuery = Lot::query()->with('product.container');
    
        if ($productId) {
            // Filtrer les lots par produit
            $lotsQuery->where('product_id', $productId);
        }
    
        if ($search) {
            // Rechercher par ID du lot, date de péremption ou nom du produit
            $lotsQuery->where(function ($query) use ($search) {
                $query->where('id', 'like', "%$search%")
                      ->orWhere('expiration_date', 'like', "%$search%")
                      ->orWhereHas('product', function ($productQuery) use ($search) {
                          $productQuery->where('name', 'like', "%$search%");
                      });
            });
        }
    
        // Récupérer les lots et le produit (si un ID est passé)
        $lots = $lotsQuery->paginate(10); // 10 lots par page
        $product = $productId ? Product::find($productId) : null;
    
        return view('lots.manage', [
            'lots' => $lots,
            'product' => $product,
            'search' => $search,
        ]);
    }
    

    public function create(Product $product)
    {
        return view('lots.create', compact('product'));
    }

    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'production_date' => 'required|date',
            'sterilization_date' => 'nullable|date',
            'expiration_date' => 'nullable|date',
            'stock' => 'required|integer|min:0',
            'photos.*' => 'image|mimes:jpg,png,jpeg|max:2048',
        ]);
    
        // Convertir les dates au format 'YYYY-MM-DD'
        $productionDate = Carbon::parse($validated['production_date'])->toDateString();
        $sterilizationDate = isset($validated['sterilization_date']) 
            ? Carbon::parse($validated['sterilization_date'])->toDateString() 
            : null;
        $expirationDate = isset($validated['expiration_date']) 
            ? Carbon::parse($validated['expiration_date'])->toDateString() 
            : null;
    
        // Création du lot (une seule fois)
        $lot = $product->lots()->create([
            'production_date' => $productionDate,
            'sterilization_date' => $sterilizationDate,
            'expiration_date' => $expirationDate,
            'stock' => $validated['stock'],
        ]);
    
        // Associe l'emplacement "Maison" avec le stock initial
        $defaultLocation = Location::firstOrCreate(['name' => 'Maison']);
        $lot->locations()->attach($defaultLocation->id, ['stock' => $validated['stock']]);
    
        // Met à jour le stock du produit
        $product->updateStockFromLots();
    
        // Ajouter des photos si présentes
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('lot_photos', 'public');
                $lot->photos()->create(['photo_path' => $path]);
            }
        }
    
        return redirect()->route('lots.manage', ['product_id' => $product->id])
            ->with('success', 'Lot créé avec succès.');
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
        $validated = $request->validate([
            'production_date' => 'required|date',
            'sterilization_date' => 'nullable|date',
            'expiration_date' => 'nullable|date',
            'stock' => 'required|integer|min:0',
        ]);
    
        // Convertir les dates au format 'YYYY-MM-DD'
        $productionDate = Carbon::parse($validated['production_date'])->toDateString();
        $sterilizationDate = isset($validated['sterilization_date'])
            ? Carbon::parse($validated['sterilization_date'])->toDateString()
            : null;
        $expirationDate = isset($validated['expiration_date'])
            ? Carbon::parse($validated['expiration_date'])->toDateString()
            : null;
    
        // Mise à jour des champs
        $lot->update([
            'production_date' => $productionDate,
            'sterilization_date' => $sterilizationDate,
            'expiration_date' => $expirationDate,
            'stock' => $validated['stock'],
        ]);
    
        return redirect()->route('lots.manage', ['product_id' => $lot->product_id])
            ->with('success', 'Lot mis à jour avec succès.');
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

        // Données mensuelles pour le tableau
        $monthlyData = Lot::select(
            DB::raw('strftime("%Y-%m", production_date) as year_month'),
            DB::raw('COUNT(*) as lot_count'),
            DB::raw('SUM(stock) as total_jars')
        )
        ->groupBy('year_month')
        ->orderBy('year_month', 'asc')
        ->get();

        return view('dashboard', compact('expiredLots', 'currentMonthLots', 'monthlyData'));
    }

    public function manageLocations(Lot $lot)
    {
        $locations = Location::all(); // Tous les emplacements
        $lotLocations = $lot->locations; // Emplacements liés au lot
    
        return view('lots.locations', compact('lot', 'locations', 'lotLocations'));
    }

    public function updateLocations(Request $request, Lot $lot)
    {
        $request->validate([
            'locations' => 'required|array',
            'locations.*.stock' => 'required|integer|min:0',
        ]);
    
        $updatedStocks = $request->input('locations');
    
        foreach ($updatedStocks as $locationId => $data) {
            $currentStock = $lot->locations()->where('locations.id', $locationId)->first()->pivot->stock ?? 0;
    
            if ($data['stock'] < $currentStock) {
                // Réduction du stock
                $difference = $currentStock - $data['stock'];
    
                // Vérifiez si la réduction est possible (stock minimum, etc.)
                if ($difference > $currentStock) {
                    return redirect()->back()->with('error', 'Réduction impossible, stock insuffisant.');
                }
    
                $lot->locations()->updateExistingPivot($locationId, [
                    'stock' => $data['stock'],
                ]);
            } else {
                // Sinon, mettez simplement à jour le stock
                $lot->locations()->updateExistingPivot($locationId, [
                    'stock' => $data['stock'],
                ]);
            }
        }
    
        // Recalculer les stocks du lot et du produit
        $lot->updateStockFromLocations();
    
        return redirect()->route('lots.manage', ['product_id' => $lot->product_id])
        ->with('success', 'Lot mis à jour avec succès.');    }
    
    
    

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
        $lot->locations()->updateExistingPivot($request->from_location_id, [
            'stock' => $fromLocation->pivot->stock - $request->quantity,
        ]);

        if ($toLocation) {
            $lot->locations()->updateExistingPivot($request->to_location_id, [
                'stock' => $toLocation->pivot->stock + $request->quantity,
            ]);
        } else {
            $lot->locations()->attach($request->to_location_id, ['stock' => $request->quantity]);
        }

        // Synchronise le stock total du lot
        $lot->updateStockFromLocations();

        return redirect()->route('lots.locations.manage', $lot->id)->with('success', 'Stock déplacé avec succès.');
    }

    public function updateStockFromLocations()
    {
        // Recalculer le stock total du lot à partir des emplacements
        $totalStock = $this->locations->sum(function ($location) {
            return $location->pivot->stock;
        });
    
        $this->stock = $totalStock;
        $this->save();
    
        // Recalculer le stock du produit parent
        $this->product->update([
            'stock' => $this->product->lots->sum('stock'),
        ]);
    }
    

    public function transferStock(Request $request, Lot $lot)
    {
        $request->validate([
            'source_location_id' => 'required|exists:locations,id',
            'destination_location_id' => 'required|exists:locations,id|different:source_location_id',
            'amount' => 'required|integer|min:1',
        ]);
    
        $sourceLocation = $lot->locations()->where('location_id', $request->source_location_id)->first();
        $destinationLocation = $lot->locations()->where('location_id', $request->destination_location_id)->first();
    
        if (!$sourceLocation || $sourceLocation->pivot->stock < $request->amount) {
            return redirect()->back()->with('error', 'Stock insuffisant dans l\'emplacement source.');
        }
    
        // Déduire le stock de l'emplacement source
        $lot->locations()->updateExistingPivot($request->source_location_id, [
            'stock' => $sourceLocation->pivot->stock - $request->amount,
        ]);
    
        // Ajouter le stock à l'emplacement de destination
        if ($destinationLocation) {
            // Mise à jour si l'emplacement existe déjà pour ce lot
            $lot->locations()->updateExistingPivot($request->destination_location_id, [
                'stock' => $destinationLocation->pivot->stock + $request->amount,
            ]);
        } else {
            // Créer une nouvelle association si l'emplacement de destination n'existe pas
            $lot->locations()->attach($request->destination_location_id, [
                'stock' => $request->amount,
            ]);
        }
    
        return redirect()->route('lots.locations.manage', $lot->id)
                         ->with('success', 'Stock transféré avec succès.');
    }


}
