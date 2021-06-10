<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNotificationRequest;
use App\Models\Notifications;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class NotificationController extends Controller
{
    /**
     * Show the form for editing Notification.
     * Method: GET
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $notification = Notifications::latest()->first();
        return view('notification/home', $data = ['notification' => $notification]);
    }

    /**
     * Update Notification.
     * Method: PUT
     * @return \Illuminate\Http\Response
     */
    public function update(StoreNotificationRequest $request)
    {
        try {
            return DB::transaction(function() use ($request) {
                
                $notification = new Notifications();
                $notification->advance_notice_days = $request->advance_notice_days;
                $notification->chatwork_flag       = $request->chatwork_flag == 'on' ? 1 : 0;
                $notification->chatwork_text       = $request->chatwork_text;
                $notification->mail_flag           = $request->mail_flag == 'on' ? 1 : 0;
                $notification->mail_text           = $request->mail_text;

                if (!$notification->save()) {
					throw new \Exception('Notification update failed');
                }
            
                return redirect(route('admin.notification.edit'))->with(['notification' => $notification, 'success' => '更新が完了しました。']);
            });

        
        } catch (Exception $e) {
			return back()->withErrors($e->getMessage());
        }
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
