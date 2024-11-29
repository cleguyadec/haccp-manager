<?php

namespace App\Models;

use App\Models\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    // Autorisez uniquement les champs nécessaires pour l'assignation de masse
    protected $fillable = [
        'name',
        'container_id',
        'price',
        'stock',
        'description',
        'is_sterilized',
        'raw_material_cost',
    ];

    protected $casts = [
        'is_sterilized' => 'boolean',
    ];

    // Relation avec le modèle Container
    public function container()
    {
        return $this->belongsTo(Container::class);
    }

    public function lots()
    {
        return $this->hasMany(Lot::class);
    }

    public function updateStockFromLots()
    {
        $totalStock = $this->lots->sum('stock');
        $this->update(['stock' => $totalStock]);
    }
}
