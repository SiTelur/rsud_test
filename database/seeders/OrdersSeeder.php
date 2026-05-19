<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $user = DB::table('users')->first();
        $product = DB::table('products')->first();

        if ($user && $product) {
            $total = (float) $product->price * 2;

            DB::table('orders')->insert([
                ['user_id' => $user->id, 'product_id' => $product->id, 'qty' => 2, 'total_price' => (int) round($total), 'status' => 'pending', 'created_at' => $now, 'updated_at' => $now],
            ]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
