<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Calendar;
use App\Models\Orders;
use App\Models\PostHistories;
use App\Models\Skips;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Mockery\Generator\StringManipulation\Pass\Pass;
use PostHistoriesSeeder;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index($year, $month)
    {
        # 指定された年、月の当番表を出力する

        $calendar       = new Calendar;

        
        # 昨日投稿した人の順番order_numberを取得
        $last_day_post        = PostHistories::where('post_flag', 0)->first();
        $last_day_order_point = Orders::where('user_id', $last_day_post->user_id)->first();
        
        $today = Carbon::now('Asia/Tokyo');
        $date_str = sprintf('%04d-%02d', $year, $month);
        # $year-$month-01 ex.2021-06-01
        $designated_date = new Carbon($date_str);
        
        # 指定された年月による場合分け
        # if文の条件をメソッドに
        if ($today->month == $designated_date->month) {
            # post_histories と orders, skipsをカレンダーと結合
            
            # dates(日付)を用意する
            $dates = $calendar->getCurrentMonthCalendar($year, $month);
            
            # おおよそ一月分のordersを取得
            $orders = $last_day_order_point->getOrders($last_day_order_point->order_number);

            # カレンダーの初めから昨日までの投稿を取得
            $d = $dates[array_key_first($dates)]->format('Y-m-d');
            $recent_post_histories = PostHistories::where('post_day', '>=', $d)
                                                  ->join('users', 'post_histories.user_id', '=', 'users.id')
                                                  ->get();
            
            # 今日からカレンダーの終わりまでのスキップを取得
            $last_date = $dates[array_key_last($dates)];
            $future_d = $last_date->format('Y-m-d');
            $today_d  = $today->format('Y-m-d');
            $skips = Skips::where('skip_day', '<', $future_d)
                          ->where('skip_day', '>=', $today_d)
                          ->get();

            # orders, recent_post_histories, skips を datesと結合
            foreach ($recent_post_histories as $ph) {
                
                $key_date = substr($ph->post_day, 0, 10);
                $dates[$key_date] = ['date' => $dates[$key_date], 'user' => $ph];
                
            }

            foreach ($skips as $skip) {
                
                $key_date = substr($skip->skip_day, 0, 10);
                $dates[$key_date] = ['date' => $dates[$key_date], 'user' => $skip];

            }

            # $datesと$ordersの結合
            $n = 0;
            for ($i=0; $i < count($dates); $i++) {

                $key_date = substr($today->format('Y-m-d'), 0, 10);
                
                if (!array_key_exists($key_date, $dates)) {
                    # $datesの最後まで達したらそこで終了
                    break;
                }

                if (!is_array($dates[$key_date])) {
                    # Arrayでない＝skipテーブルに日付がなかった
                    # ＝投稿日
                    if (!$dates[$key_date]->isWeekday()) {
                        # 休日の場合、$todayに一日加算してcontinue
                        $today->addDay();
                        continue;
                    }

                    $dates[$key_date] = ['date' => $dates[$key_date], 'user' => $orders->get($n)];
                    $n ++;
                }

                # Arrayの場合＝skipテーブルに日付があった
                # ＝投稿しない日
                $today->addDay();
            }
            
            return view('home', $data = ['dates' => $dates, 'currentMonth' => $month, 'orders' => $orders,
                                         'skips' => $skips, 'post_histories' => $recent_post_histories]);

        } elseif ($today->month < $month) {
            # 未来
            # orders, skipsを日付と結合

            # dates(日付)を用意する
            $dates = $calendar->getDatesFromToday($today, $year, $month);
            
            # 指定された年月までのordersを取得する
            $m = ($designated_date->year - $today->year) * 12;
            $orders = $last_day_order_point->getOrdersFromToday($last_day_order_point->order_number, ($month - $today->month) + $m);

            # 今日から指定された年月までのスキップを取得
            $last_d = $dates[array_key_last($dates)]->format('Y-m-d');
            $today_d  = $today->format('Y-m-d');
            $skips = Skips::where('skip_day', '<', $last_d)
                          ->where('skip_day', '>=', $today_d)
                          ->get();

            # orders, skips を datesと結合
            foreach ($skips as $skip) {
                
                $key_date = substr($skip->skip_day, 0, 10);
                $dates[$key_date] = ['date' => $dates[$key_date], 'user' => $skip];

            }

            $n = 0;
            for ($i=0; $i < count($dates); $i++) {

                $key_date = substr($today->format('Y-m-d'), 0, 10);
                
                if (!array_key_exists($key_date, $dates)) {
                    # $datesの最後まで達したらそこで終了
                    break;
                }

                if (!is_array($dates[$key_date])) {
                    # Arrayでない＝skipテーブルに日付がなかった
                    # ＝投稿日
                    if (!$dates[$key_date]->isWeekday()) {
                        # 休日の場合、$todayに一日加算してcontinue
                        $today->addDay();
                        continue;
                    }

                    $dates[$key_date] = ['date' => $dates[$key_date], 'user' => $orders->get($n)];
                    $n ++;
                }

                # Arrayの場合＝skipテーブルに日付があった
                # ＝投稿しない日
                $today->addDay();
            }


            # dates と year, month からカレンダーを作る
            $c = $calendar->getCurrentMonthCalendar($year, $month);
            $d = [];
            foreach ($c as $date) {
              $d[$date->format('Y-m-d')] = $dates[$date->format('Y-m-d')];
              }

            return view('home', $data = ['dates' => $d, 'currentMonth' => $month, 'orders' => $orders,
                                         'skips' => $skips]);

        } elseif ($today->month > $month) {
            # 過去
            # post_historiesと結合

            # dates(日付)を用意する
            $dates = $calendar->getCurrentMonthCalendar($year, $month);

            # 指定月の投稿を取得
            $d = $dates[array_key_first($dates)]->format('Y-m-d');
            $t = $dates[array_key_last($dates)]->format('Y-m-d');
            $post_histories = PostHistories::where('post_day', '>=', $d)
                                           ->where('post_day', '<', $t)
                                           ->join('users', 'post_histories.user_id', '=', 'users.id')
                                           ->get();

            # orders, recent_post_histories, skips を datesと結合
            foreach ($post_histories as $ph) {
    
                $key_date = substr($ph->post_day, 0, 10);
                $dates[$key_date] = ['date' => $dates[$key_date], 'user' => $ph];
                
            }
            return view('home', $data = ['dates' => $dates, 'currentMonth' => $month, 'post_histories' => $post_histories]);
                     
        }

    }


    /**
     * 
     * redirect to /{year}/{month}
     */
    public function root()
    {
        $now = Carbon::now();
        $path = sprintf("dashboard/%04d/%02d", $now->year, $now->month);
        return redirect($path);
    }

}
