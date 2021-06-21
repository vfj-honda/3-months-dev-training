<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ChatworkAPIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationsController extends Controller
{
    /**
     * 通知テキストのプレビューを表示する
     * 
     * @return 
     */
    public function preview(Request $request)
    {
        $chatwork_api_service = new ChatworkAPIService();
        $notification_text = $chatwork_api_service->get_notice_text($request->input('chatwork_text'));
        return response()->json([
            'converted_notification_text' => $notification_text,
        ], 200);
    }
}
