<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CSVFileUploadRequest extends FormRequest
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
            'csv_file' => [
                'required', // 必須チェック
                'max:1024', // ファイルサイズ上限
                'file', // file属性でアップロードされたファイル
                'mimes:csv,txt', // 拡張子
                'mimetypes:text/plain', // Laravelが判定したmimetypes
            ],
        ];
    }

    public function messages()
    {
        return [
            'csv_file.required' => 'ファイルを指定してください。',
            'csv_file.file' => 'アップロードが出来ませんでした',
            'csv_file.mimes' => 'CSVファイルを指定してください。',
            'csv_file.mimetypes' => 'CSVファイルを指定してください。',
        ];
    }

}
