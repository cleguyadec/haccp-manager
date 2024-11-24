<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Lot;
use App\Models\Location;

class ProductLotSeeder extends Seeder
{
    public function run()
    {
        // Vérifiez ou créez des emplacements, y compris "maison"
        $defaultLocations = ['maison', 'entrepôt A', 'entrepôt B'];
        foreach ($defaultLocations as $locationName) {
            Location::firstOrCreate(['name' => $locationName], [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Créez les produits et leurs lots
        Product::factory(30)
            ->create()
            ->each(function ($product) {
                // Associer 30 lots à chaque produit
                Lot::factory(30)->create([
                    'product_id' => $product->id,
                ]);

                // Mettre à jour le stock total du produit
                $product->update(['stock' => $product->lots()->sum('stock')]);
            });
    }
}


