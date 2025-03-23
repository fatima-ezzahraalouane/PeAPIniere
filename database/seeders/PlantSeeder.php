<?php

namespace Database\Seeders;

use App\Models\Plant;
use App\Models\Category;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Plant::create([
            'name' => 'Basilic',
            'description' => 'Plante aromatique utilisée en cuisine.',
            'price' => 12.50,
            'category_id' => Category::where('name', 'Plantes Aromatiques')->first()->id
        ]);

        Plant::create([
            'name' => 'Menthe',
            'description' => 'Plante rafraîchissante, idéale pour le thé.',
            'price' => 10.00,
            'category_id' => Category::where('name', 'Plantes Aromatiques')->first()->id
        ]);

        Plant::create([
            'name' => 'Aloe Vera',
            'description' => 'Plante médicinale avec propriétés apaisantes.',
            'price' => 18.00,
            'category_id' => Category::where('name', 'Plantes Médicinales')->first()->id
        ]);
    }
}
