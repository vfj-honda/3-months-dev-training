<?php

namespace App\Services;

use App\Models\Calendar;
use App\Models\Orders;
use App\Models\PostHistories;
use App\Models\Skips;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CalendarService {
    
    public function getCurrentMonthCalendar(int $year, int $month)
    {
        # post_histories と orders, skipsをカレンダーと結合

        $calendar = new Calendar;
        
        # dates(日付)を用意する
        # datesの中身は
        # ['2021-05-01' => Carbon('2021-05-01'), ...]
        $dates = $calendar->getCalendar($year, $month);
        
        # 最新の投稿を取得
        $yesterday_order_point = PostHistories::latest('post_day')
                                              ->join('orders', 'post_histories.user_id', '=', 'orders.user_id')
                                              ->select('orders.order_number')
                                              ->first();
        # 一巡
        $orders = Orders::first()
                        ->getOrders($yesterday_order_point->order_number);

        # カレンダーの初めから昨日までの投稿を取得
        $d = array_key_first($dates);
        $post_histories = PostHistories::where('post_day', '>=', $d)
                                       ->join('users', 'post_histories.user_id', '=', 'users.id')
                                       ->select('users.id', 'users.name', 'post_histories.post_day')
                                       ->get();
        
        # 今日からカレンダーの終わりまでのスキップを取得
        $today = Carbon::now();
        $end_d = array_key_last($dates);
        $start_d  = $today->format('Y-m-d');
        $skips = Skips::where('skip_day', '<', $end_d)
                        ->where('skip_day', '>=', $start_d)
                        ->orderBy('skip_day', 'asc')
                        ->get();

        # orders, recent_post_histories, skips を datesと結合
        # $dates=[
        # '2021-05-01' => ['date' => Carbon('2021-05-01'), 'user' => obj], ...
        # ]
        foreach ($post_histories as $ph) {
            
            $key_date = substr($ph->post_day, 0, 10);
            $dates[$key_date] = ['date' => $dates[$key_date], 'user' => $ph];
            
        }

        foreach ($skips as $skip) {
            
            $key_date = substr($skip->skip_day, 0, 10);
            $dates[$key_date] = ['date' => $dates[$key_date], 'user' => $skip];

        }

        $today = $start_d;
        $n = 0;
        foreach ($dates as $key => $value) {

            if (!is_array($value)) {
                # Arrayでない＝$valueはCarbon＝skips,post_historiesテーブルに日付がない
                
                if (!$value->isWeekday()) {
                    # 休日の場合何もしない
                    continue;
                }

                if ($value->lt($today)) {
                    # 今日より前には何もしない
                    continue;
                }

                $dates[$key] = ['date' => $dates[$key], 'user' => $orders->get($n)];
                $n ++;

            }

        }

        return $dates;
    }

    public function getPastMonthCalendar(int $year, int $month)
    {
        # dates(日付)を用意する
        $calendar = new Calendar;
        $dates = $calendar->getCalendar($year, $month);

        # 指定月の投稿を取得
        $start_d = array_key_first($dates);
        $end_d   = array_key_last($dates);
        $post_histories = PostHistories::where('post_day', '>=', $start_d)
                                        ->where('post_day', '<', $end_d)
                                        ->join('users', 'post_histories.user_id', '=', 'users.id')
                                        ->select('users.id', 'users.name', 'post_histories.post_day')
                                        ->get();

        # post_histories を datesと結合
        foreach ($post_histories as $ph) {

            $key_date = substr($ph->post_day, 0, 10);
            $dates[$key_date] = ['date' => $dates[$key_date], 'user' => $ph];
            
        }

        return $dates;

    }

    public function getFutureMonthCalendar(int $year, int $month)
    {
        $calendar = new Calendar;
        $dates = $calendar->getCalendar($year, $month);

        # 今日からカレンダーの初めまでの平日数を取得
        $today = Carbon::now();
        $target_d = array_key_first($dates);
        $week_days_count = $today->diffInWeekdays($target_d);

        # 今日からカレンダーの初めまでのスキップ数を取得
        $start_d = $today->format('Y-m-d');
        $skips = Skips::where('skip_day', '>=', $start_d)
                      ->where('skip_day', '<', $target_d)
                      ->get();
        $skip_days_count = $skips->count();

        # 投稿日 = 平日 - スキップする日
        $post_days_count = $week_days_count - $skip_days_count;

        # 一巡を取得
        $yesterday_order_point = PostHistories::latest('post_day')
                                              ->join('orders', 'post_histories.user_id', '=', 'orders.user_id')
                                              ->select('orders.order_number')
                                              ->first();
        $orders = Orders::first()
                        ->getOrders($yesterday_order_point->order_number);

        # 
        $index = $post_days_count % $orders->count();

        # $dates と Skipsを結合
        $start_d = array_key_first($dates);
        $end_d = array_key_last($dates);
        $skips = Skips::where('skip_day', '>=', $start_d)
                      ->where('skip_day', '<', $end_d)
                      ->get();

        foreach ($skips as $skip) {

            $key_date = substr($skip->skip_day, 0, 10);
            $dates[$key_date] = ['date' => $dates[$key_date], 'user' => $skip];
        
        }

        # $dates と $ordersを結合
        $n = $index;
        foreach ($dates as $key => $value) {
            # $ordersを一周したら初めに戻る
            $n = $n == $orders->count() ? 0 : $n;

            if (!is_array($value)) {
                # Arrayでない＝$valueはCarbon＝skipsテーブルに日付がない
                
                if ($value->isWeekend()) {
                    # 休日の場合
                    continue;
                }

                $dates[$key] = ['date' => $dates[$key], 'user' => $orders->get($n)];
                $n ++;

            }

        }

        return $dates;

    }
}