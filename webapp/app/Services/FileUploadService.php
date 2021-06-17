<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Http\Request;

class FileUploadService {

    public function csv_upload(Request $request, $upload_file_name)
    {
        # storage/app/mediaにcsvファイルをアップロード
        $filename = Carbon::now()->format('Y-m-d-h:m:s');
        $request->file($upload_file_name)->storeAs('media', $filename.'.csv');
        $path = storage_path() . '/app/media' . '/' . $filename . '.csv';
        return $path;

    }
}
