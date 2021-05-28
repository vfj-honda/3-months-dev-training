<?php

namespace App\Models;

use Carbon\Carbon;

class Calendar
{
     /**
     * 
     *
     * @return list
     */
    public function getCalendarDates(int $year, int $month)
    {
        $dateStr = sprintf('%04d-%02d-01', $year, $month);
        $date = new Carbon($dateStr);
        // 左上に前月データを入れるため、カレンダーをずらす
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
            $dates[] = $date->copy();
        }
        
        return $dates;
    }
}