<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Autorisez uniquement les champs nécessaires pour l'assignation de masse
    protected $fillable = ['name', 'container_id', 'price', 'stock'];

    // Relation avec le modèle Container
    public function container()
    {
        return $this->belongsTo(Container::class);
    }
}
