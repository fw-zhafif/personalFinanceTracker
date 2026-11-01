<?php

namespace Database\Seeders;


use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::create(['name' => 'Gaji', 'type' => 'income']);
        Category::create(['name' => 'Bonus', 'type' => 'income']);
        Category::create(['name' => 'Makanan & Minuman', 'type' => 'expense']);
        Category::create(['name' => 'Transportasi', 'type' => 'expense']);
        Category::create(['name' => 'Hiburan', 'type' => 'expense']);
    }
}
