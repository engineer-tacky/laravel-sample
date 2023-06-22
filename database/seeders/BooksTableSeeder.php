<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Category;

class BooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      $categories = [
        Category::factory()->create(['title' => 'programming']),
        Category::factory()->create(['title' => 'design']),
        Category::factory()->create(['title' => 'management']),
      ];

      foreach ($categories as $category) {
        Book::factory(2)->create(['category_id' => $category->id]);
      }
    }
}
