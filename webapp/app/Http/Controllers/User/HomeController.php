<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Calendar;

use Illuminate\Http\Request;


class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $calender = new Calendar;

        return view('home', $data = ['dates' => $calender->getCalendarDates(2021, 5), 'currentMonth' => 5]);
    }

}
