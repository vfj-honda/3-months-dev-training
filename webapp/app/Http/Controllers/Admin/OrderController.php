<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Switch the order.
     * Method: PUT
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function switch(Request $request)
    {
        return redirect();
    }

    public function show()
    {
        $orders = Orders::orderBy('order_number', 'asc')
                        ->join('users', 'orders.user_id', '=', 'users.id')
                        ->select('orders.order_number', 'users.name')
                        ->get();
        return view('employee/order_list', $data = ['orders' => $orders]);
    }
}
