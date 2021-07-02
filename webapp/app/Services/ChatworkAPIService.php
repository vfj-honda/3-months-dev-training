<?php

namespace App\Services;

use App\Models\FixedPostDates;
use App\Models\Notifications;
use App\Models\Orders;
use App\Models\PostHistories;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class ChatworkAPIService {
    
    
    /**
     * メッセージを送信する
     * 
     * 
     */ 
    public function post()
    {
        # API送信に必要な設定
        $endpoint = config('const.CHATWORK_API.ENDPOINT');
        $api_token = config('const.CHATWORK_API.TOKEN');
        $method = 'POST';
        $headers = [
            'X-ChatWorkToken'  => $api_token,
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];
        
        # 通知内容取得
        $notice = Notifications::latest()->first();
        if ($notice->chatwork_flag == 0) {
            return 'not posted';
        }

        
        # 通知文章を作成
        $body = $this->get_notice_text($notice->chatwork_text);
        $client = new Client();

        # 送信
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
    public function get_notice_text($data){

        $today = Carbon::today();

        # 今日、明日、明後日の当番を取得
        $yesterday_post_history = PostHistories::latest('post_day')
                                                ->join('orders', 'post_histories.user_id', '=', 'orders.user_id')
                                                ->select('orders.order_number')
                                                ->first();


        $order_number = $yesterday_post_history->order_number;

        $fpd = FixedPostDates::where('fixed_post_day', '=', $today->format('Y-m-d'))->first();

        if (!$fpd == null) {
            $order_number = Orders::where('user_id', '=', $fpd->user_id)->first()->order_number - 1;
        }

        $orders    = Orders::first()
                        ->getOrders($order_number);
        
        $today     = $orders->get(0);
        $next      = $orders->get(1);
        $afternext = $orders->get(2);

        $data = str_replace('$today', '[To:'.$today->chatwork_id.']'.$today->name.'さん', $data);
        $data = str_replace('$next', '[To:'.$next->chatwork_id.']'.$next->name.'さん', $data);
        $data = str_replace('$afternext', '[To:'.$afternext->chatwork_id.']'.$afternext->name.'さん', $data);

        return $data;

    }
}