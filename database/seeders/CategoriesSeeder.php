<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        DB::table('categories')->insert([
            ['name' => 'Electronics', 'slug' => Str::slug('Electronics'), 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Books', 'slug' => Str::slug('Books'), 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Clothing', 'slug' => Str::slug('Clothing'), 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
