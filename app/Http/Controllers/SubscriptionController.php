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
    public function index(Request $request)
    {
        $res=new Result();
        $gym_id=$this->guard()->user()->gym_id;
        
        $list=Subscription::whereHas('adherent',function($q) use ($gym_id,$request) { 
            if($request->has('phone'))
            {
                $phone=$request->get('phone');    
                $q->where('gym_id',$gym_id)->where('phone',$phone);
            }
            else
            {
                $q->where('gym_id',$gym_id);
            }
         });


        if($request->has('coach_id'))
        {
            
            $coach_id=(int)$request->get('coach_id');
            $list->whereHas('typesSubscription',function($q) use ($coach_id) { 
                $q->whereHas('activity',function($q) use ($coach_id) { 
                    $q->whereHas('coach',function($q) use ($coach_id) { 
                        $q->where('id',$coach_id);
                    });
                 });
            });
        }

        if($request->has('type_subscriptions_id'))
        {
            $type_subscriptions_id=$request->get('type_subscriptions_id');   
            $list->where('type_subscriptions_id',$type_subscriptions_id);
        }
        $res->successPaginate($list);
        return response()->json($res);
    }

    public function add(Request $request)
    {
        $data=$request->all();
        $data['status']=true;
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
        $date=new DateTime('now');
        $data['cards']['count_subscription_accept']=Subscription::where('status',true)->whereHas('adherent',function($q) use ($gym_id) { $q->where('gym_id',$gym_id); })->count();
        $data['cards']['count_subscription_termine']=Subscription::where('status',true)->whereDate('end_at','<',$date)->whereHas('adherent',function($q) use ($gym_id) { $q->where('gym_id',$gym_id); })->count();;
        $data['cards']['count_subscription_encours']=Subscription::where('status',true)->whereDate('end_at','<',$date)->where('status',true)->whereDate('end_at','>',$date)->whereDate('start_at','<',$date)->whereHas('adherent',function($q) use ($gym_id) { $q->where('gym_id',$gym_id); })->count();
        $list=Subscription::whereHas('adherent',function ($q) use($gym_id){ $q->where('gym_id',$gym_id);})->select('type_subscriptions_id')->pluck('type_subscriptions_id')->toArray();
        $profit=0;
        foreach($list as $idSub) { 
             $profit=$profit+TypeSubscription::find($idSub)['price']; 
            }
        $data['cards']['count_subscription_profit']=$profit;
        $res->success($data);
        return response()->json($res);
    }
}
