<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lot extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'expiration_date', 'stock','production_date',
    'sterilization_date'];

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
    
    public function calculateExpirationDate()
    {
        if ($this->product->is_sterilized) {
            // Produit stérilisé : date de stérilisation + 9 mois
            $this->expiration_date = $this->sterilization_date ? $this->sterilization_date->addMonths(9) : null;
        } else {
            // Produit non stérilisé : date de production + 3 jours
            $this->expiration_date = $this->production_date ? $this->production_date->addDays(3) : null;
        }
    }
}

