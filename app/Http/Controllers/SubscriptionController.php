<?php

namespace App\Http\Controllers;

use App\Libs\Result;
use App\Models\Activity;
use App\Models\Subscription;
use DateTime;
use Illuminate\Http\Request;

class SubscriptionController extends BaseAuthController
{
    public function index()
    {
        $res=new Result();
        $gym_id=$this->guard()->user()->gym_id;
        $list=Subscription::whereHas('adherent',function($q) use ($gym_id) { $q->where('gym_id',$gym_id); });
        $res->successPaginate($list);
        return response()->json($res);
    }

    public function add(Request $request)
    {
        $sub=new Subscription();
        $res= $sub->CreateOne($request->all());
        return response()->json($res);
    }

    public function changeStatus(int $id,Request $request)
    {
        $res=new Result();
        $sub=new Subscription();
        $sub->roleDataCreate=[];
        $res= $sub->UpdateOne($request->all('status'),$id);
        $res->success();
        return response()->json($res);
    }

    public function update($id,Request $request)
    {
        $res=new Result();
        $sub=new Subscription();
        $sub->UpdateOne($request->all(),$id);
        $res->success();
        return response()->json($res);
    }
    public function cards()
    {
        $date=(new DateTime())->format('Y-m-d');
        $res=new Result();
        $cards=array();
        $gym_id=$this->guard()->user()->gym_id;
        $cards['sales']=Subscription::whereHas('adherent',function($q) use ($gym_id) { $q->where('gym_id',$gym_id); })->where('end_at','>',$date)->get();
        $res->success($cards);
        return response()->json($res);
    }
}
