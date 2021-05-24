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
            'user_id' => '1',
            'post_day' => '2021-05-25 01:01:01',
            'post_flag' => 0,
        ]);

        DB::table('post_histories')->insert([
            'user_id' => '1',
            'post_day' => '2021-05-26 07:33:38',
            'post_flag' => 1,
            'url' => 'https://hogehoge.jp',
            'title' => '仕事ライフハック',
        ]);

        DB::table('post_histories')->insert([
            'user_id' => '2',
            'post_day' => '2021-05-26 01:01:01',
            'post_flag' => 0,
        ]);

        DB::table('post_histories')->insert([
            'user_id' => '2',
            'post_day' => '2021-05-27 07:33:38',
            'post_flag' => 1,
            'url' => 'https://hogehoge.jp',
            'title' => '仕事ライフハック',
            'deleted_at' => '2021-04-12 04:22:34',
        ]);

        DB::table('post_histories')->insert([
            'user_id' => '3',
            'post_day' => '2021-05-27 01:01:01',
            'post_flag' => 0,
        ]);

    }
}
