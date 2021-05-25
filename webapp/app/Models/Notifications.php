<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    protected $table = 'notifications';
    protected $fillable = ['advance_notice_days', 'chatwork_flag', 'mail_flag', 'chatwork_text', 'mail_text', 'created_at'];
}
