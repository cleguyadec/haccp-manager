<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Container;

class ContainerSeeder extends Seeder
{
    public function run()
    {
        $containers = [
            ['size' => 'Petite boîte'],
            ['size' => 'Moyenne boîte'],
            ['size' => 'Grande boîte'],
        ];

        Container::insert($containers);
    }
}
