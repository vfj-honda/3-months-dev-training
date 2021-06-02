<?php

namespace App\Models;

use Carbon\Carbon;

class Calendar
{
     /**
     * 
     *
     * @return array
     */
    public function getCurrentMonthCalendar(int $year, int $month)
    {
        $dateStr = sprintf('%04d-%02d-01', $year, $month);
        $date = new Carbon($dateStr);
        // 左上に前月の日付を入れるため、カレンダーをずらす
        $date->subDay($date->dayOfWeek);

        // 同上。右下の隙間のための計算。
        $count = 31 + $date->dayOfWeek;
        $count = ceil($count / 7) * 7;
        $tmp_date = $date->copy();
        
        // $countが足りなかった場合、プラスする。
        if ($tmp_date->addDays($count)->month == $month) {
            $count += 7;
        }
        
        $dates = [];

        for ($i = 0; $i < $count; $i++, $date->addDay()) {
            // copyしないと全部同じオブジェクトを入れてしまうことになる
            $dates[$date->format('Y-m-d')] = $date->copy();
        }
        
        return $dates;
    }

    /**
     * 
     *
     * @return array
     */
    public function getDatesFromToday(Carbon $today, int $year, int $month)
    {
        
        $date = $today->copy();
        $dates = [];

        while ($date->month < $month + 2) {
            $dates[$date->format('Y-m-d')] = $date->copy();
            $date->addDay();
        }

        return $dates;
    }

    /**
     * 
     *
     * @return list
     */
    public function getOneYearCalendar(int $year)
    {
        $date_str = sprintf('%04d-01-01', $year);
        $date = new Carbon($date_str);

        $dates = [];

        for ($i = 0; $i < 365; $i++, $date->addDay()) {
            $dates[] = $date->copy();
        }

        return $dates;
    }

    /**
     * 
     *
     * @return int
     */
    public function getCountDaysAfterToday(string $today)
    {
        return 5;
    }
}