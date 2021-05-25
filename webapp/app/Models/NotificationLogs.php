<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationLogs extends Model
{
    protected $table = 'notification_logs';
    protected $fillable = ['noticed_at', 'notification_id'];
}
