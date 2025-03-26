<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'title' => 'Categorie 1',
                'slug'  => 'category-1',
            ],
            [
                'title' => 'Categorie 2',
                'slug'  => 'category-2',
            ],
            [
                'title' => 'Categorie 3',
                'slug'  => 'category-3',        
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }
    }
}
