<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{

    /**
     * Show the form for creating a new employee.
     * Method: GET
     * @return \Illuminate\Http\Response
     */
    public function create(){
        return view('employee/create');
    }

    /**
     * Show the employee list.
     * Method: GET
     * @return \Illuminate\Http\Response
     */
    public function show(){
        return view('employee/list');
    }

}
