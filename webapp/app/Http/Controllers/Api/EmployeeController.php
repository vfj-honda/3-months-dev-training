<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class EmployeeController extends Controller
{
    public function destroy(Request $request)
    {
        try {
            return DB::transaction(function() use ($request) {
 
                Log::setDefaultDriver('operation');
                # 1) get record
                $employee = User::find($request->user_id);
                $operator = User::find($request->operator);
                Log::info($operator->name . 'が社員「' . $employee->name . '」を削除');

                # 2) delete
                $employee->order->delete();
                $employee->delete();

                return response()->json([
                    'success_text' => '社員の削除が完了しました',
                ], 200);
            }); 
 
 
        } catch(\Exception $e) {
            return response()->json([
                'error_text' => $e->getMessage(),
            ], 500); 
        }
    }
}
