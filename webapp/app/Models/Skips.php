<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Skips extends Model
{
    use SoftDeletes;
    
    protected $table = 'skips';
    protected $fillable = ['skip_day', 'user_id', 'deleted_at'];

    

}
