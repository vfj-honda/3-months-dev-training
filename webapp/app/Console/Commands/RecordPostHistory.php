<?php

namespace App\Console\Commands;

use App\Models\FixedPostDates;
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
        Log::setDefaultDriver('batch');
        # 今日の投稿者をpost_historyに記録する
        DB::transaction( function(){

            $yesterday_post_history = PostHistories::latest('post_day')
                                                   ->join('orders', 'post_histories.user_id', '=', 'orders.user_id')
                                                   ->select('orders.order_number', 'post_histories.post_day')
                                                   ->first();

            $order_number = $yesterday_post_history->order_number;
            $order_point = Orders::where('order_number', '=', $order_number + 1)
                                 ->first();


            $post_day = substr($yesterday_post_history->post_day, 0, 10);
            $today    = Carbon::today();
            if ($post_day == $today->format('Y-m-d')) {
                # 二重insert防止
                $this->info('Post History record has already done.');
                Log::info('Post History record has already done.');
                return;
            }

            # skipsに今日の日付があるかチェック
            $res = Skips::where('skip_day', '=', $today->format('Y-m-d'))
                        ->first();
            if (!$res == null) {
                return;
            }

            # 今日が「投稿者を指定した投稿日」かチェック
            $fpd = FixedPostDates::where('fixed_post_day', '=', $today->format('Y-m-d'))->first();
            if (!$fpd == null) {

                $order_point = Orders::where('user_id', '=', $fpd->user_id)->first();

            }

            

            $ph = new PostHistories;
            $ph->post_day = $today;
            $ph->user_id  = $order_point->user_id;

            if( $ph->save() ) {
                $this->info('Post History recorded successfully.'.' ['.$today.']');
                Log::info('Post History recorded successfully.'.' ['.$today.']');
            } else {
                $this->info('Post History record failed.'.' ['.$today.']');
                Log::error('Post History record failed.'.'['.$today.']');
            }

        });
    }
}
