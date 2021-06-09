<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Calendar;
use App\Models\Orders;
use App\Models\PostHistories;
use App\Models\Skips;
use App\Services\CalendarService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

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


        
        if (!$this->is_valid($year, $month)) {
            Log::info('not valid');
            return App::abort(404);
        }
        $today = Carbon::now('Asia/Tokyo');
        
        # 指定された年月による現在、未来、過去の場合分け
        if ($this->is_current($today, $year, $month)) {

            # カレンダーの取得
            $calendar_service = new CalendarService;
            $dates = $calendar_service->getCurrentMonthCalendar($year, $month);

            # skipsの取得
            $end_d    = array_key_last($dates);
            $start_d  = $today->format('Y-m-d');
            $skips    = Skips::where('skip_day', '<', $end_d)
                             ->where('skip_day', '>=', $start_d)
                             ->orderBy('skip_day', 'asc')
                             ->get();
                
            return view('home', $data = ['dates' => $dates, 'currentYear' => $year, 'currentMonth' => $month, 'skips' => $skips]);

        } elseif ($this->is_future($today, $year, $month)) {
            # 未来
            # orders, skipsを日付と結合

            $calendar_service = new CalendarService;
            $dates = $calendar_service->getFutureMonthCalendar($year, $month);

            # skipsの取得
            $end_d    = array_key_last($dates);
            $start_d  = array_key_first($dates);
            $skips    = Skips::where('skip_day', '<', $end_d)
                             ->where('skip_day', '>=', $start_d)
                             ->orderBy('skip_day', 'asc')
                             ->get();

            return view('home', $data = ['dates' => $dates, 'currentYear' => $year, 'currentMonth' => $month,
                                         'skips' => $skips]);

        } elseif ($this->is_past($today, $year, $month)) {
            # 過去
            # post_historiesと結合

            $calendar_service = new CalendarService;
            $dates = $calendar_service->getPastMonthCalendar($year, $month);

            return view('home', $data = ['dates' => $dates, 'currentYear' => $year, 'currentMonth' => $month]);
                     
        }

    }

    private function is_current(Carbon $today, int $year, int $month)
    {
        if ($today->year == $year && $today->month == $month) {
            return true;
        }

        return false;
    }

    private function is_future(Carbon $today, int $year, int $month)
    {
        if ($today->year < $year) {
            return true;
        }

        if ($today->year == $year && $today->month < $month) {
            return true;
        }

        return false;
    }

    private function is_past(Carbon $today, int $year, int $month)
    {
        if ($today->year > $year) {
            return true;
        }

        if ($today->year == $year && $today->month > $month) {
            return true;
        }

        return false;
    }

    private function is_valid($year, $month)
    {

        if (!is_numeric($year) || !is_numeric($month)) {
            return false;
        }
        
        $months = [1,2,3,4,5,6,7,8,9,10,11,12];
        
        if (!in_array($month, $months)) {
            return false;
        }
        
        return true;

    }

    /**
     * 
     * redirect to /{year}/{month}
     */
    public function root()
    {
        $now = Carbon::now();
        $path = sprintf("admin/dashboard/%04d/%02d", $now->year, $now->month);
        return redirect($path);
    }

}
