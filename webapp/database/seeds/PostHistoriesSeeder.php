<?php

use Illuminate\Database\Seeder;

class PostHistoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('post_histories')->insert([
            'user_id' => '26',
            'post_day' => '2021-07-06 00:00:00',
            'post_flag' => 0,
        ]);


    }
}
