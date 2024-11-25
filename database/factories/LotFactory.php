<?php

namespace Database\Factories;

use App\Models\Lot;
use App\Models\Product;
use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

class LotFactory extends Factory
{
    protected $model = Lot::class;

    public function definition()
    {
        return [
            'product_id' => Product::inRandomOrder()->first()->id ?? 1,
            'expiration_date' => $this->faker->dateTimeBetween('now', '+2 years'),
            'stock' => $this->faker->numberBetween(10, 100),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Lot $lot) {
            // Associer à tous les emplacements avec un stock par défaut (0)
            $locations = Location::all();
            foreach ($locations as $location) {
                $lot->locations()->attach($location->id, [
                    'stock' => $location->name === 'Maison' ? $lot->stock : 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }
}

