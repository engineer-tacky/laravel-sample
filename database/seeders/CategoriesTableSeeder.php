<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
          ['title' => 'programming'],
          ['title' => 'design'],
          ['title' => 'management'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
