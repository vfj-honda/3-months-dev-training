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
            'user_id' => '40',
            'post_day' => '2021-05-26 01:01:01',
            'post_flag' => 0,
        ]);

        DB::table('post_histories')->insert([
            'user_id' => '41',
            'post_day' => '2021-05-27 01:01:01',
            'post_flag' => 0,
        ]);

        DB::table('post_histories')->insert([
            'user_id' => '42',
            'post_day' => '2021-05-28 07:33:38',
        ]);

        DB::table('post_histories')->insert([
            'user_id' => '43',
            'post_day' => '2021-05-29 01:01:01',
            'post_flag' => 0,
        ]);

        DB::table('post_histories')->insert([
            'user_id' => '1',
            'post_day' => '2021-05-30 07:33:38',
            'post_flag' => 0,
        ]);


    }
}
