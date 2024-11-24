<?php

namespace App\Http\Controllers;

use App\Models\Lot;
use App\Models\Product;
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
    
        // Ajoute le stock du lot au stock total du produit
        $product->increment('stock', $request->stock);
    
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
        $request->validate([
            'expiration_date' => 'nullable|date',
            'stock' => 'required|integer|min:0',
        ]);

        // Calcul de la différence entre le nouveau stock et l'ancien
        $stockDifference = $request->stock - $lot->stock;

        // Met à jour le lot
        $lot->update([
            'expiration_date' => $request->expiration_date,
            'stock' => $request->stock,
        ]);

        // Met à jour le stock du produit
        $lot->product->increment('stock', $stockDifference);

        return redirect()->route('lots.manage')->with('success', 'Lot mis à jour et stock ajusté.');
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
}
