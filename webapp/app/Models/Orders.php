<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;




class Orders extends Model
{

    protected $table = 'orders';
    protected $fillable = ['user_id', 'order_number', 'created_at', 'updated_at'];
    protected $primaryKey = 'user_id';


    /**
     * 指定された期間のorderを返す
     * 
     * @return Collection of Orders binding User
     */
    public function getOrdersFromPointDay(int $user_id, $start_day, $end_day)
    {
        $order = $this->where('user_id', '=', $user_id)->first();
        $s_d = new Carbon($start_day);
        
        $order_count = $s_d->diffInDays($end_day); # 何日分か

        $orders = $this->getOrders($order->order_number - 1);

        $orders = $orders->chunk($order_count);
        
        return $orders->get(0);
    }



    /**
     * $n で受け取った回数分、リストの末尾にリストを結合させる
     * 
     * @return Collection
     */
    private function union(Collection $list, Collection $result, int $n)
    {
        if ($n == 1) {
            return $result->concat($list);
        }
        return $this->union($list, $result->concat($list), $n-1);
    }

    /**
     * 受け取った$order_numberから一巡分のordersを返す
     * 
     * @return Collection
     */
    public function getOrders(int $order_number)
    {

        $orders = $this->where('order_number', '>', $order_number)
                       ->orderBy('order_number', 'asc')
                       ->join('users', 'orders.user_id', '=', 'users.id')
                       ->select('users.name', 'users.id', 'orders.order_number', 'users.chatwork_id')
                       ->get();
        $tmp    = $this->where('order_number', '<=', $order_number)
                       ->orderBy('order_number', 'asc')
                       ->join('users', 'orders.user_id', '=', 'users.id')
                       ->select('users.name', 'users.id', 'orders.order_number', 'users.chatwork_id')
                       ->get();
        $orders = $orders->concat($tmp);

        return $orders;
    }


    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
