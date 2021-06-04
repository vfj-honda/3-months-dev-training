<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SwitchOrderRequest;
use App\Models\Orders;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Switch the order.
     * Method: PUT
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function switch(SwitchOrderRequest $request)
    {
        try {

            return DB::transaction(function() use ($request){
    
                $num1 = $request->switch_1;
                $num2 = $request->switch_2;
        
                # 入れ替え
                $order1 = Orders::where('order_number', $num1)->first();
                $order2 = Orders::where('order_number', $num2)->first();

                
                if (!$order1->update(['order_number' => $num2])) {
                    throw new \Exception('Switch Order is failed.');
                }

                if (!$order2->update(['order_number' => $num1])) {
                    throw new \Exception('Switch Order is failed.');
                }


                return redirect(route('admin.employee.order_list'));

                
            });

        } catch (Exception $e) {
            return back()->withErrors($e->getMessage());
        }
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
