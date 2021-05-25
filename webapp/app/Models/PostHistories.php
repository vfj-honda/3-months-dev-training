<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostHistories extends Model
{
    use SoftDeletes;

    protected $table = 'post_histories';
    protected $fillable = ['user_id', 'post_day', 'post_flag', 'url', 'title', 'color', 'deleted_at', 'created_at', 'updated_at'];

}
