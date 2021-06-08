<?php

namespace App\Services;

use App\Models\Notifications;
use App\Models\Orders;
use App\Models\PostHistories;
use GuzzleHttp\Client;

class ChatworkAPIService {
    
    
    /**
     * メッセージを送信する
     * 
     * 
     */ 
    public function post()
    {
        $endpoint = config('const.CHATWORK_API.ENDPOINT');
        $api_token = config('const.CHATWORK_API.TOKEN');
        $method = 'POST';
        $headers = [
            'X-ChatWorkToken'  => $api_token,
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];    
        
        $notice = Notifications::latest()->first();
        if ($notice->chatwork_flag == 0) {
            return 'not posted';
        }

        # 通知文章を作るために、今日、明日、明後日の当番を取得    
        $yesterday_post_history = PostHistories::latest('post_day')
                                                ->join('orders', 'post_histories.user_id', '=', 'orders.user_id')
                                                ->select('orders.order_number')
                                                ->first();
        
        $order_number = $yesterday_post_history->order_number + 1;
        $orders       = Orders::first()
                              ->getOrders($order_number);
        
        $today = $orders->get(0);
        $next = $orders->get(1);
        $afternext = $orders->get(2);
        

        $body = $this->get_notice_text($notice->chatwork_text, $today, $next, $afternext);
        $client = new Client();

        $response = $client->request($method, $endpoint, [
            'headers' => $headers,
            'body'    => $body,
        ]);    

        return $response->getStatusCode() == 200 ? true : false;

    }    

    /** 
     *通知メッセージを作成するメソッド 
     * 
     * @return string
    */ 
    private function get_notice_text(string $data, Orders $today, Orders $next, Orders $afternext){

        $data = str_replace('$today', '[To:'.$today->chatwork_id.']'.$today->name.'さん', $data);
        $data = str_replace('$next', '[To:'.$next->chatwork_id.']'.$next->name.'さん', $data);
        $data = str_replace('$afternext', '[To:'.$afternext->chatwork_id.']'.$afternext->name.'さん', $data);

        return $data;

    }
}