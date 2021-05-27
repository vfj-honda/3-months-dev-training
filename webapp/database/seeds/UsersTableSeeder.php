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
        DB::table('users')->insert([
            'name' => '本多誠浩',
            'email' => 'mhonda@vitalify.jp',
            'chatwork_id' => 5708259,
            'gender' => 1,
            'authority' => 1,
            'password' => bcrypt('vitalify')
        ]);
        DB::table('users')->insert([
            'name' => '本田正樹',
            'email' => 'book0324.lk@gmail.com',
            'chatwork_id' => 1234567,
            'gender' => 1,
            'authority' => 2,
        ]);
        DB::table('users')->insert([
            'name' => '橋本未来',
            'email' => 'mhashimoto@vitalify.jp',
            'chatwork_id' => 7584639,
            'gender' => 1,
            'authority' => 2,
        ]);
        DB::table('users')->insert([
            'name' => '佐藤一朗',
            'email' => 'isatou@vitalify.jp',
            'chatwork_id' => 1470375,
            'gender' => 1,
            'authority' => 2,
        ]);
    }
}
