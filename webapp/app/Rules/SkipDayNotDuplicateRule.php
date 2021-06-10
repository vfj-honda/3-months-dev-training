<?php

namespace App\Rules;

use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SkipDayNotDuplicateRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {

        $date = new Carbon($value);
        $re = new Request($request=['skip_day' => $date->format('Y-m-d')]);
        try {
            
            $re->validate(['skip_day' => 'unique:skips,skip_day,NULL,id,deleted_at,NULL']);
        
        } catch (Exception $e) {
            return false;
        }
        return true;
}

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'その日付は既に登録されています。';
    }
}
