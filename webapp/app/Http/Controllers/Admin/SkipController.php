<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skips;
use Exception;
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
    public function create(Request $request)
    {
        try {
            
            return DB::transaction(function() use($request){

                $skip = new Skips();
                $skip->skip_day = $request->create_skip_day;

                if (!$skip->save()) {
                    throw new \Exception('Skip saving is failed.');
                }

                return redirect(route('admin.home'));
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
                
                return redirect(route('admin.home'));
            });

        } catch (Exception $e) {
            return back()->withErrors($e->getMessage());
        }
        
    }
}