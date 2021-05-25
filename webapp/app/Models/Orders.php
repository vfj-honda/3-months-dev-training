<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Orders extends Model
{
    use SoftDeletes;

    protected $table = 'orders';
    protected $fillable = ['user_id', 'order_number', 'created_at', 'updated_at'];
}
