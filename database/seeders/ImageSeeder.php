<?php

namespace Database\Seeders;

use App\Models\Plant;
use App\Models\Image;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plants = Plant::all();

        foreach ($plants as $plant) {
            $plant->images()->createMany([
                ['url' => 'https://example.com/images/' . $plant->slug . '_1.jpg'],
                ['url' => 'https://example.com/images/' . $plant->slug . '_2.jpg'],
            ]);
        }
    }
}
