<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    // Autorise l'assignation de masse pour le champ 'name'
    protected $fillable = ['name'];
    
    public function lots()
    {
        return $this->belongsToMany(Lot::class, 'lot_location')->withPivot('stock');
    }
}
