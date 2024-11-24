<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Location;

class LocationSeeder extends Seeder
{
    public function run()
    {
        $locations = [
            ['name' => 'Maison'],
            ['name' => 'Entrepôt 1'],
            ['name' => 'Entrepôt 2'],
        ];

        Location::insert($locations);
    }
}

