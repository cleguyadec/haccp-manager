<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lot extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'expiration_date', 'stock'];

    // Relation avec les emplacements
    public function locations()
    {
        return $this->belongsToMany(Location::class, 'lot_location')->withPivot('stock');
    }

    // Relation avec le produit
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relation avec les photos
    public function photos()
    {
        return $this->hasMany(LotPhoto::class);
    }

    // Méthode pour recalculer le stock total
    public function updateStockFromLocations()
    {
        // Calculer le stock total du lot à partir des emplacements
        $totalStock = $this->locations->sum(function ($location) {
            return $location->pivot->stock;
        });
    
        // Mettre à jour le stock du lot
        $this->update(['stock' => $totalStock]);
    
        // Mettre à jour le stock du produit associé
        $this->product->updateStockFromLots();
    }
}

