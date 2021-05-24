<?php

use Illuminate\Database\Seeder;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('orders')->insert([
            'user_id' => '1',
            'order_number' => 1,
        ]);
        DB::table('orders')->insert([
            'user_id' => '2',
            'order_number' => 2,
        ]);
        DB::table('orders')->insert([
            'user_id' => '3',
            'order_number' => 3,
        ]);
        DB::table('orders')->insert([
            'user_id' => '4',
            'order_number' => 4,
        ]);
        
    }
}
