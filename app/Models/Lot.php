<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lot extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'expiration_date', 'stock'];

    // Relation avec le produit
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relation pour les photos
    public function photos()
    {
        return $this->hasMany(LotPhoto::class);
    }

    public function locations()
    {
        return $this->belongsToMany(Location::class, 'lot_location')->withPivot('stock')->withTimestamps();
    }

    public function updateStockFromLocations()
    {
        $totalStock = $this->locations->sum('pivot.stock');
        $this->stock = $totalStock;
        $this->save();

        // Mettre à jour le stock du produit
        $this->product->updateStockFromLots();
    }
}

