<?php

namespace App\Http\Controllers;

use App\Libs\Result;
use App\Models\Activity;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        $res=new Result();
        $res->success(Subscription::all());
        return response()->json($res);
    }

    public function add(Request $request)
    {
        $activity=new Subscription();
        $res= $activity->CreateOne($request->all());
        return response()->json($res);
    }

    public function delete(int $id)
    {
        $res=new Result();
        Subscription::destroy($id);
        $res->success();
        return response()->json($res);
    }

    public function update($id,Request $request)
    {
        $res=new Result();
        $activity=new Subscription();
        $activity->UpdateOne($request->all(),$id);
        $res->success();
        return response()->json($res);
    }
}
