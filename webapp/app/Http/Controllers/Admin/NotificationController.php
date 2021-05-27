<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Show the form for editing Notification.
     * Method: GET
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        return view('notification/home');
    }

    /**
     * Show the list of notification logs.
     * Method: GET
     * @return \Illuminate\Http\Response
     */
    public function logs()
    {
        return view('notification/logs');
    }
}
