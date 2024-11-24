<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LotPhoto extends Model
{
    use HasFactory;

    protected $fillable = ['lot_id', 'photo_path'];

    // Relation avec le lot
    public function lot()
    {
        return $this->belongsTo(Lot::class);
    }
}

