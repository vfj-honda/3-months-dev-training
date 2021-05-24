<?php

use Illuminate\Database\Seeder;

class SkipsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('skips')->insert([
            'skip_day' => '2021-06-5 01:01:01',
        ]);
        DB::table('skips')->insert([
            'skip_day' => '2021-06-06 01:01:01',
        ]);
        DB::table('skips')->insert([
            'skip_day' => '2021-06-13 01:01:01',
        ]);
    }
}
