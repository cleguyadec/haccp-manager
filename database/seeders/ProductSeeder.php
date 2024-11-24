<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            ['name' => 'Produit 1', 'container_id' => 1, 'price' => 10.50, 'stock' => 50],
            ['name' => 'Produit 2', 'container_id' => 2, 'price' => 20.00, 'stock' => 30],
            ['name' => 'Produit 3', 'container_id' => 3, 'price' => 15.00, 'stock' => 40],
        ];

        Product::insert($products);
    }
}

