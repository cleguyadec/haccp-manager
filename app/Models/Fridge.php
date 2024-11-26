<?php

namespace App\Models;

use App\Models\TemperatureImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Fridge extends Model
{
    use HasFactory;

    protected $fillable = ['name','location'];

    public function temperatures()
    {
        return $this->hasMany(TemperatureImage::class);
    }
}

