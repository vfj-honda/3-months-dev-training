<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogController extends Controller
{
    /**
     * Show the logs.
     * Method: GET
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return view('logs/home');
    }
}
