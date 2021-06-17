<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFixedPostDatesRequest;
use App\Models\FixedPostDates;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FixedPostDatesController extends Controller
{
    

    public function create(StoreFixedPostDatesRequest $request)
    {

        try {
            return DB::transaction(function () use ($request) {

                $fpd = new FixedPostDates;
                $fpd->fixed_post_day = $request->create_fixed_post_day;
                $fpd->user_id        = $request->user_id;

                if (!$fpd->save()) {
                    throw new Exception('create fixed post day is failed!');
                }

                return back()->with('success', '正常に作成されました');

            });
        } catch (Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }


    public function destroy(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {

                $fpd = FixedPostDates::find($request->fixed_post_date_id);

                $fpd->delete();
                back()->with('success', '正常に取り消されました');

            });
        } catch (Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }



}
