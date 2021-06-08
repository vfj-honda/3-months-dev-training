<?php

namespace App\Console\Commands;

use App\Models\Orders;
use App\Models\PostHistories;
use App\Models\Skips;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RecordPostHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'post_history:record';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '本日の当番をpost_historyに記録する';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        # 今日の投稿者をpost_historyに記録する
        DB::transaction( function(){

            $yesterday_post_history = PostHistories::latest('post_day')
                                                   ->join('orders', 'post_histories.user_id', '=', 'orders.user_id')
                                                   ->select('orders.order_number', 'post_histories.post_day')
                                                   ->first();

            $order_number = $yesterday_post_history->order_number;
            $post_day     = substr($yesterday_post_history->post_day, 0, 10);

            $today = Carbon::now()->format('Y-m-d');

            if ($post_day == $today) {
                $this->info('Post History record has already done.');
                return;
            }

            # skipsに今日の日付があるかチェック
            $res = Skips::where('skip_day', '=', $today)
                        ->first();
            if (!$res == null) {
                return;
            }

            $today_order = Orders::where('order_number', '=', $order_number+1)
                                 ->first();

            $ph = new PostHistories;
            $ph->post_day = $today;
            $ph->user_id  = $today_order->user_id;

            if( $ph->save() ) {
                $this->info('Post History recorded successfully.'.' ['.$today.']');
                Log::info('Post History recorded successfully.'.' ['.$today.']');
            } else {
                Log::error('Post History record failed'.'['.$today.']');
            }

        });
    }
}
