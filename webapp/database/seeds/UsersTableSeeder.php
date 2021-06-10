<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv = fopen(resource_path().'/blogorderlist_2021_05_27.csv', $mode='r');

        $data = fgetcsv($csv); # 一行目を捨てる
        
        while($data = fgetcsv($csv, $delimiter=',')){
            DB::table('users')->insert([
                'name' => $data[0],
                'chatwork_id' => $data[1],
                'email' => $data[2],
            ]);
        }
    }
}
