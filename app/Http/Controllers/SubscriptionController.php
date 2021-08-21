<?php

namespace App\Http\Controllers;

use App\Libs\Result;
use App\Models\Activity;
use App\Models\Subscription;
use App\Models\TypeSubscription;
use App\Models\User;
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
    public function getSettings()
    {
        $gym_id=$this->guard()->user()->gym_id;
        $res=new Result();
        $data['list_coachs']=User::where('role','coach')->where('gym_id',$gym_id)->select('first_name','last_name','id')->get();
        $data['list_offers']=TypeSubscription::whereHas('activity',function($q) use ($gym_id) {
            $q->whereHas('coach', function ($q) use ($gym_id) { $q->where('gym_id', $gym_id); });
        })->select(['name','id'])->get();
        $data['cards']['count_subscription_accept']=0;
        $data['cards']['count_subscription_termine']=0;
        $data['cards']['count_subscription_encours']=0;
        $data['cards']['count_subscription_profit']=0;
        $res->success($data);
        return response()->json($res);
    }
}
