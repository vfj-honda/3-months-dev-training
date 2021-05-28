<?php

use App\Models\User;
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

        $csv = fopen(resource_path().'/blogorderlist_2021_05_27.csv', $mode='r');

        $data = fgetcsv($csv);

        $index = 0;
        while($data = fgetcsv($csv, $delimiter=',')){
            $user = User::where('chatwork_id', $data[1])->firstOrFail();
            $index += 1;
            DB::table('orders')->insert([
                'user_id' => $user->id,
                'order_number' => $index,
            ]);
        }

    }
}
