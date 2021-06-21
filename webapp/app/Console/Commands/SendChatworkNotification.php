<?php

namespace App\Console\Commands;

use App\Models\Skips;
use App\Services\ChatworkAPIService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendChatworkNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chatwork_notice:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'チャットワークの通知を送信する';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Log::setDefaultDriver('batch');
        $today = Carbon::today();

        # skipsに今日の日付があるかチェック
        $res = Skips::where('skip_day', '=', $today->format('Y-m-d'))
                    ->first();

        if (!$res == null) {
            return;
        }

        $chatwork_service = new ChatworkAPIService;
        $result = $chatwork_service->post();
        
        if($result == true) {
            $this->info('send chatwork message successfully.');
            Log::info('send chatwork message successfully.'.'['.$today->format('Y-m-d').']');
        } elseif ($result == 'not posted') {
            Log::info('notification does not posted' );
        } elseif ($result == false) {
            $this->error('send chatwork message failed.');
            Log::error('send chatwork message failed.'.'['.$today->format('Y-m-d').']');
        }

    }
}
