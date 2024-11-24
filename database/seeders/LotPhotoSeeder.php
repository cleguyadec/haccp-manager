<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LotPhoto;

class LotPhotoSeeder extends Seeder
{
    public function run()
    {
        $photos = [
            ['lot_id' => 1, 'photo_path' => 'images/lot1_image1.jpg'],
            ['lot_id' => 1, 'photo_path' => 'images/lot1_image2.jpg'],
            ['lot_id' => 2, 'photo_path' => 'images/lot2_image1.jpg'],
        ];

        LotPhoto::insert($photos);
    }
}

