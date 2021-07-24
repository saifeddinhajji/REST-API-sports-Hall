<?php

namespace App\Http\Controllers;

use App\Libs\Result;
use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{

    public function index()
    {
        $res=new Result();
        $res->success(Activity::all());
        return response()->json($res);
    }

    public function add(Request $request)
    {
        $activity=new Activity();
        $res= $activity->CreateOne($request->all());
        return response()->json($res);
    }



    public function update($id,Request $request)
    {
        $res=new Result();
        $activity=new Activity();
        $activity->UpdateOne($request->all(),$id);
        $res->success();
        return response()->json($res);
    }
}
