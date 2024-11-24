<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Champs autorisés pour l'assignation de masse
    protected $fillable = ['name', 'container_size', 'price', 'stock'];
}
