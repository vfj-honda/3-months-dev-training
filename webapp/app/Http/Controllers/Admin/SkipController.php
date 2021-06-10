<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSkipRequest;
use App\Models\Skips;
use Carbon\Carbon;
use Exception;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SkipController extends Controller
{

    /**
     * Create Skip.
     * Method: POST
     * 
     */
    public function create(StoreSkipRequest $request)
    {
        try {
            
            return DB::transaction(function() use($request){

                $skip = new Skips();
                $skip->skip_day = $request->create_skip_day;

                if (!$skip->save()) {
                    throw new \Exception('Skip saving is failed.');
                }

                return back()->with('success', '正常に作成されました。');
            });
            
        } catch (Exception $e) {
            return back()->withErrors($e->getMessage());
        }
        
    }

    /**
     * Destroy Skip.
     * Method: DELETE
     */
    public function destroy(Request $request)
    {
        try {
            
            return DB::transaction(function() use ($request) {
                
                $skip = Skips::find($request->delete_skip_id);

                $skip->delete();
                
                return back()->with('success', '正常に削除されました。');
            });

        } catch (Exception $e) {
            return back()->withErrors($e->getMessage());
        }
        
    }

    /**
     * 
     * 
     * 
     */
    public function import(Request $request)
    {
        try {
            
            $file_upload_service = new FileUploadService;
            # アップロードされたファイルを保存
            $path = $file_upload_service->csv_upload($request, 'csv_file');
    
            $csv = fopen($path, 'r');
            $data = fgetcsv($csv);
            while ($data = fgetcsv($csv, $delimiter = ',')) {
                
                $skip           = new Skips;
                $date           = new Carbon($data[0]);
                $skip->skip_day = $date->format('Y-m-d');
                $re = new Request($request=['skip_day' => $date->format('Y-m-d')]);
                try {
                    
                    $this->validate($re, ['skip_day' => 'unique:skips,skip_day,NULL,id,deleted_at,NULL']);
                
                } catch (Exception $e) {
                    continue;
                }
                $skip->save();
    
            }
            
            return back()->with('success', 'インポートが正常に終了しました。');

        } catch (Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }
}
