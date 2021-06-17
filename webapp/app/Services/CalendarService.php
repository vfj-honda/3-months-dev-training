<?php

namespace App\Services;

use App\Models\Calendar;
use App\Models\FixedPostDates;
use App\Models\Orders;
use App\Models\PostHistories;
use App\Models\Skips;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CalendarService {
    
    public function getCurrentMonthCalendar(int $year, int $month)
    {
        # post_histories, orders, skips, fixed_post_dates を日付と結合

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
        $today = Carbon::today();
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

        # 今日からカレンダーの終わりまでの「投稿者が指定された投稿日」を取得
        $fpd = FixedPostDates::where('fixed_post_day', '>=', $start_d)
                            ->where('fixed_post_day', '<=', $end_d)
                            ->orderBy('fixed_post_day', 'asc')
                            ->get();
        Log::info($fpd);
        if ($fpd->count() == 0) {
            
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

        } else {

            # ordersと日付の結合
            $count = $today->diffInDays($fpd->get(0)->fixed_post_day);
            $index_day = $today;

            for ($h=0; $h < $count; $h++) {

                $value = $dates[$index_day->format('Y-m-d')];

                if (!is_array($value)) {
                    # Arrayでない＝$valueはCarbon＝skips,post_historiesテーブルに日付がない
                    
                    if (!$value->isWeekday()) {
                        # 休日の場合何もしない
                        $index_day->addDay();
                        continue;
                    }

                    $dates[$index_day->format('Y-m-d')] =  ['date' => $dates[$index_day->format('Y-m-d')], 'user' => $orders->get($h)];
                }

                $index_day->addDay();
            }

            for ($i=0; $i < $fpd->count(); $i++) { 

                $f = $fpd->get($i);
                $e = $fpd->get($i+1) == null ? $end_d : $fpd->get($i+1)->fixed_post_day;

                Log::info('$e: '.$e);

                # 指定日から指定日(or カレンダーの最終日)までのorderを取得
                $fpd_orders = Orders::first()
                                ->getOrdersFromPointDay($f->user_id, $f->fixed_post_day, $e);

                Log::info('指定日から指定日までのorder');
                Log::info($fpd_orders);
                
                $index_day = new Carbon($f->fixed_post_day);
                
                $count     = $index_day->diffInDays($e);

                $n = 0;
                for ($j=0; $j < $count; $j++) {

                    $value = $dates[$index_day->format('Y-m-d')];
                    if (!is_array($value)) {
                        # Arrayでない＝$valueはCarbon＝skips,post_historiesテーブルに日付がない
                        
                        if (!$value->isWeekday()) {
                            # 休日の場合何もしない
                            $index_day->addDay();
                            continue;
                        }

                        $dates[$index_day->format('Y-m-d')] =  ['date' => $dates[$index_day->format('Y-m-d')], 'user' => $fpd_orders->get($n)];
                        $n++;
                    }

                    $index_day->addDay();
                }
            }

            return $dates;
        }
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
        Log::info('future Calendar');
        $calendar = new Calendar;
        $dates = $calendar->getCalendar($year, $month);

        $today = Carbon::today();

        # 月初より前の指定投稿日を取得
        $latest_fixed_post_day = FixedPostDates::where('fixed_post_day', '<', array_key_first($dates))
                                               ->where('fixed_post_day', '>=', $today->format('Y-m-d'))
                                               ->latest('fixed_post_day')->first();
        $index_day = $latest_fixed_post_day == null ? $today : new Carbon($latest_fixed_post_day->fixed_post_day);

        Log::info($index_day);

        # index_dayからカレンダーの初めまでの平日数を取得
        $target_d = array_key_first($dates);
        $week_days_count = $index_day->diffInWeekdays($target_d);

        # index_dayからカレンダーの初めまでのスキップ数を取得
        $s_d = $index_day->format('Y-m-d');
        $skips   = Skips::groupBy('skip_day')
                        ->where('skip_day', '>=', $s_d)
                        ->where('skip_day', '<', $target_d)
                        ->select('skip_day')
                        ->get();

        $skip_days_count = $skips->count();

        # 投稿日数 = 平日数 - スキップする日数
        $post_days_count = $week_days_count - $skip_days_count;

        # 一巡を取得
        if ($index_day->isSameDay($today)) {
            
            $yesterday_order_point = PostHistories::latest('post_day')
                                                  ->join('orders', 'post_histories.user_id', '=', 'orders.user_id')
                                                  ->select('orders.order_number')
                                                  ->first();
            $orders = Orders::first()
                            ->getOrders($yesterday_order_point->order_number);
        } else {
            
            $fpd_order_point = Orders::where('user_id', '=', $latest_fixed_post_day->user_id)->first();
            $orders = Orders::first()
                            ->getOrders($fpd_order_point->order_number - 1);
            Log::info($fpd_order_point);
        }

        # 順番を調整
        $order_index = $post_days_count % $orders->count();

        # $dates と Skipsを結合
        $first_d = array_key_first($dates);
        $last_d = array_key_last($dates);
        $skips = Skips::groupBy('skip_day')
                      ->where('skip_day', '>=', $first_d)
                      ->where('skip_day', '<', $last_d)
                      ->select('skip_day')
                      ->get();

        foreach ($skips as $skip) {

            $key_date = substr($skip->skip_day, 0, 10);
            $dates[$key_date] = ['date' => $dates[$key_date], 'user' => $skip];
        
        }

        # カレンダーの初めから終わりまでの「投稿者が指定された投稿日」を取得
        $fpd = FixedPostDates::where('fixed_post_day', '>', $first_d)
                            ->where('fixed_post_day', '<=', $last_d)
                            ->orderBy('fixed_post_day', 'asc')
                            ->get();

        if ($fpd->count() == 0) {
            
            # $dates と $ordersを結合
            $n = $order_index;
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

        } else {
            
            # カレンダーの初めから最初の「投稿者を指定した投稿日」までのordersと日付の結合
            $first_d = new Carbon(array_key_first($dates));
            $count = $first_d->diffInDays($fpd->get(0)->fixed_post_day);
            $index_day = $first_d;

            $n = $order_index;
            for ($h=0; $h < $count; $h++) {

                $n = $n == $orders->count() ? 0 : $n;

                $value = $dates[$index_day->format('Y-m-d')]->copy();

                if (!is_array($value)) {
                    # Arrayでない＝$valueはCarbon＝skips,post_historiesテーブルに日付がない
                    
                    if (!$value->isWeekday()) {
                        # 休日の場合何もしない
                        $index_day->addDay();
                        continue;
                    }

                    $dates[$index_day->format('Y-m-d')] =  ['date' => $dates[$index_day->format('Y-m-d')], 'user' => $orders->get($n)];
                    $n++;
                }

                $index_day->addDay();
            }


            for ($i=0; $i < $fpd->count(); $i++) { 

                $f = $fpd->get($i);
                $e = $fpd->get($i+1) == null ? array_key_last($dates) : $fpd->get($i+1)->fixed_post_day;

                Log::info('$e: '.$e);


                $fpd_orders = Orders::first()
                                ->getOrdersFromPointDay($f->user_id, $f->fixed_post_day, $e);

                Log::info($fpd_orders);
                
                $index_day = new Carbon($f->fixed_post_day);
                
                $count     = $index_day->diffInDays($e);

                $n = 0;
                for ($j=0; $j < $count; $j++) {

                    $value = $dates[$index_day->format('Y-m-d')];
                    Log::info($index_day->format('Y-m-d'));
                    if (!is_array($value)) {
                        # Arrayでない＝$valueはCarbon＝skips,post_historiesテーブルに日付がない
                        
                        if (!$value->isWeekday()) {
                            # 休日の場合何もしない
                            $index_day->addDay();
                            continue;
                        }

                        $dates[$index_day->format('Y-m-d')] =  ['date' => $dates[$index_day->format('Y-m-d')], 'user' => $fpd_orders->get($n)];
                        $n++;
                    }

                    $index_day->addDay();
                }
            }

            return $dates;


        }

    }
}