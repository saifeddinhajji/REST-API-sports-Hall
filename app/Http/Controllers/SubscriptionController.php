<?php

namespace App\Http\Controllers;

use App\Libs\Result;
use App\Models\Activity;
use App\Models\Subscription;
use App\Models\TypeSubscription;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use http\Env\Response;
use Illuminate\Http\Request;

class SubscriptionController extends BaseAuthController
{
    public function index(Request $request)
    {
        $res=new Result();
        $gym_id=$this->guard()->user()->gym_id;

        $list=Subscription::whereHas('adherent',function($q) use ($gym_id,$request) {
     /*       if($request->has('phone')  && $request->get('phone') != "null")
            {
                $phone=$request->get('phone');
              //  $q->where('gym_id',$gym_id)->where('phone',$phone);
            }

         //       $q->where('gym_id',$gym_id);*/

         });


        if($request->has('coach_id') && $request->get('coach_id') != "null")
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

        if($request->has('type_subscriptions_id') && $request->get('type_subscriptions_id') != "null")
        {
            $type_subscriptions_id=$request->get('type_subscriptions_id');
            $list->where('type_subscriptions_id',$type_subscriptions_id);
        }
        $res->successPaginate($list->latest());
        return response()->json($res);
    }
    public function getEndAt($start_at,$duration,$type)
    {
        //
        $day= Carbon::parse($start_at);
        ;
        switch ($type)
        {
            case "day":
                return    $day->addDay($duration)->format('Y-m-d');
            case "month":
                return    $day->addMonth($duration)->format('Y-m-d');
            case "year":
                return    $day->addYear($duration)->format('Y-m-d');
        }
    }

    public function add(Request $request)
    {
        $data=$request->all();
        $data['status']=true;
        $sub=new Subscription();
        $typeSubscriptionObject=TypeSubscription::find($request->get('type_subscriptions_id'));
        $date=  $this->getEndAt($request->get('start_at'),$typeSubscriptionObject['duration'],$typeSubscriptionObject['unit']);
        $data=$request->all();
        $data['end_at']=$date;
        $res= $sub->CreateOne($data);
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
        $data=$request->all();
        $data['status']=true;
        $sub=new Subscription();
        $typeSubscriptionObject=TypeSubscription::find($request->get('type_subscriptions_id'));
        if(!$typeSubscriptionObject)
        {
            $res=new Result();
            $res->fail('subscription type not found');
            return response()->json($res);
        }
        $date=  $this->getEndAt($request->get('start_at'),$typeSubscriptionObject['duration'],$typeSubscriptionObject['unit']);
        $data=$request->all();
        $data['end_at']=$date;
        $res= $sub->UpdateOne($data,$id);
        return response()->json($res);
    }
    public function getSettings()
    {
        $gym_id=$this->guard()->user()->gym_id;
        $res=new Result();
        $data['list_coachs']=User::where('role','coach')->where('gym_id',$gym_id)->select('first_name','last_name','id')->get();
        $data['list_adherents']=User::where('role','adherent')->where('gym_id',$gym_id)->select('first_name','last_name','id')->get();
        $data['list_subscriptions']=TypeSubscription::whereHas('activity',function($q) use ($gym_id) {
            $q->whereHas('coach', function ($q) use ($gym_id) { $q->where('gym_id', $gym_id); });
        })->get();
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
