<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FixedPostDates extends Model
{
    use SoftDeletes;
    protected $table = 'fixed_post_dates';
    protected $fillable = ['fixed_post_day', 'deleted_at', 'user_id', 'updated_at', 'created_at'];

    /**
     * 日付が既に存在するかチェックする
     * 
     * @return boolean
     */
    public function isDuplicate(string $date)
    {
        $fpd = $this->where('fixed_post_day', '=', $date)->first();

        if ($fpd == null) {
            return False;
        }

        return True;
    }
}
