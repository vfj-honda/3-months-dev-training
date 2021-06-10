<?php

namespace App\Http\Requests;

use App\Rules\SkipDayNotDuplicateRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSkipRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'create_skip_day' => [new SkipDayNotDuplicateRule, 'required'],
        ];
    }


    public function messages()
    {
        return [
            'create_skip_day.required' => '日付を入力してください。'
        ];
    }
}
