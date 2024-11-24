<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Container;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word(), // Nom aléatoire
            'container_id' => Container::inRandomOrder()->first()->id ?? 1, // Contenant aléatoire ou par défaut
            'price' => $this->faker->randomFloat(2, 1, 100), // Prix entre 1€ et 100€
            'stock' => 0, // Initialisé à 0, sera ajusté via les lots
        ];
    }
}
