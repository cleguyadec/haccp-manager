<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lot;
use App\Models\Location;

class LotSeeder extends Seeder
{
    public function run()
    {
        $lots = [
            ['product_id' => 1, 'expiration_date' => now()->addDays(30), 'stock' => 20],
            ['product_id' => 2, 'expiration_date' => now()->addDays(60), 'stock' => 10],
        ];

        foreach ($lots as $lotData) {
            $lot = Lot::create($lotData);

            // Ajouter les stocks dans les emplacements
            $lot->locations()->attach([
                1 => ['stock' => 10], // Maison
                2 => ['stock' => 10], // Entrepôt 1
            ]);
        }
    }
}
