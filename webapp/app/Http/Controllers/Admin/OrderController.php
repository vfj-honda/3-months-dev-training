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


    public function insert(Request $request)
    {
        try {
                return DB::transaction(function() use ($request) {

                    if ( $request->insert_1 < $request->insert_2 ) {
                        # 小さい番号(insert_1)を大きい番号(insert_2)の後ろに入れる
                        $insert_1 = Orders::where('order_number', '=', $request->insert_1)->first();
                        $insert_1->order_number = $request->insert_2;
                        $insert_1->update();
            
                        $orders = Orders::where('order_number', '<=', $request->insert_2)
                                        ->where('order_number', '>', $request->insert_1)
                                        ->where('user_id', '!=', $insert_1->user_id)
                                        ->get();
                        
                        foreach ($orders as $o) {
                            $o->order_number -= 1;
                            $o->update();
                        }
                        
                    }

                    if ( $request->insert_1 > $request->insert_2 ) {
                        # 大きい番号(insert_1)を小さい番号(insert_2)の後ろに入れる
                        $insert_1 = Orders::where('order_number', '=', $request->insert_1)->first();
                        $insert_1->order_number = $request->insert_2 + 1;
                        $insert_1->update();

                        $orders = Orders::where('order_number', '>=', $request->insert_2 + 1)
                                        ->where('order_number', '<', $request->insert_1)
                                        ->where('user_id', '!=', $insert_1->user_id)
                                        ->get();
                        
                        foreach ($orders as $o) {
                            $o->order_number += 1;
                            $o->update();
                        }
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
