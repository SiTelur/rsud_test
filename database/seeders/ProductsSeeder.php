<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        // Temporarily disable foreign key checks to allow inserting product rows
        // when related tables may not yet contain referenced rows.
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $category = DB::table('categories')->first();
        $categoryId = $category->id ?? 1;

        DB::table('products')->insert([
            ['name' => 'Smartphone', 'category_id' => $categoryId, 'price' => 499.99, 'stock' => 50, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Novel Book', 'category_id' => $categoryId, 'price' => 19.99, 'stock' => 120, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'T-Shirt', 'category_id' => $categoryId, 'price' => 14.99, 'stock' => 200, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
