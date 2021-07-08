<?php

use Illuminate\Database\Seeder;

class NtoficationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('notifications')->insert([
            'chatwork_flag' => 0,
            'chatwork_text' => '',
            'mail_flag' => 0,
        ]);
    }
}
