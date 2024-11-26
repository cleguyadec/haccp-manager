<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemperatureImage extends Model
{
    use HasFactory;

    protected $fillable = ['fridge_id', 'image_path', 'temperature', 'captured_at'];

    public function fridge()
    {
        return $this->belongsTo(Fridge::class);
    }
}

