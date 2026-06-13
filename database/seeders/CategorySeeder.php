<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Électronique', 'Vêtements', 'Alimentation', 'Mobilier'];

        foreach ($categories as $name) {
            Category::create(['name' => $name]);
        }
    }
}
