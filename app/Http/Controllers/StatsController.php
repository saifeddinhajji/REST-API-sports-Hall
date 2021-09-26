<?php

namespace App\Http\Controllers;

use App\Libs\Result;
use App\Models\Activity;
use App\Models\Gym;
use App\Models\Offer;
use App\Models\Subscription;
use App\Models\SubscriptionGym;
use App\Models\TypeSubscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatsController extends BaseAuthController
{
    public function AdminStats()
    {
         $res=new Result();
         $stats=array();

         $stats['cards']['count_managers']=User::where('role','manager')->count();
         $stats['cards']['count_gyms']=Gym::count();
         $stats['cards']['count_offers']=Offer::count();
         $stats['cards']['count_subscriptions']=SubscriptionGym::count();

         $stats['tables']['last_add_list']=SubscriptionGym::latest()->take(5)->get();
         $stats['tables']['last_terminated_list']=SubscriptionGym::where('status',"accepter")->latest('end_at')->take(5)->get();

         $list=array();
            for ($i = 6; $i > -1; $i--) {
                $list[Carbon::now()->subDays($i)->format('Y-m-d')] = SubscriptionGym::whereDate('created_at', Carbon::now()->subDays($i))->count();
            }

        $price_of_months=array();

        for ($i = 12; $i > -1; $i--) {
            $price_of_months[Carbon::now()->subMonth($i)->format('Y-m')] =SubscriptionGym::where('status','accepter')->where(function($q) use($i){
              $q->whereYear('created_at','2021')->whereMonth('created_at',$i);
            })->select('offer_id')->pluck('offer_id')->toArray();
        }

        foreach ($price_of_months as $key => $value){
        {
            $sum=0;
            if($value==null ||count($value)==0) { $price_of_months[$key]=$sum; }
            else{
                foreach ($value as $id)  { $sum=$sum+Offer::find($id)['price']; }
                $price_of_months[$key]=$sum;
            }
        }
        }
        $stats['curve']['number_subscription_of_day']=$list;
        $stats['curve']['price_of_months']=$price_of_months;
         $res->success($stats);
         return response()->json($res);
    }
    public function ManagerStats()
    {
        $gym_id=$this->guard()->user()->gym_id;
        $res=new Result();
        $stats=array();
        $stats['cards']['count_employees']=User::whereIn('role',['coach','secretary'])->where('gym_id',$gym_id)->count();
        $stats['cards']['count_activites']=Activity::whereHas('coach',function ($q) use($gym_id){
            $q->where('gym_id',$gym_id);
        })->count();
        $stats['cards']['count_adherents']=User::where('role','adherent')->where('gym_id',$gym_id)->count();
        $stats['cards']['count_subscription']=Subscription::whereHas('adherent',function ($q) use($gym_id){
            $q->where('gym_id',$gym_id);
        })->count();

        $stats['tables']['last_add_list']=Subscription::whereHas('adherent',function ($q) use($gym_id){
            $q->where('gym_id',$gym_id);
        })->latest()->take(5)->get();
        $stats['tables']['last_terminated_list']=Subscription::where('status',true)->whereHas('adherent',function ($q) use($gym_id){
            $q->where('gym_id',$gym_id);
        })->oldest('end_at')->take(5)->get();
        $list=array();
        for ($i = 6; $i > -1; $i--) {
            $list[Carbon::now()->subDays($i)->format('Y-m-d')] = Subscription::whereHas('adherent',function ($q) use($gym_id){
                $q->where('gym_id',$gym_id);
            })->whereDate('created_at', Carbon::now()->subDays($i))->count();
        }
        $price_of_months=array();
        for ($i = 12; $i > -1; $i--) {
            $price_of_months[Carbon::now()->subMonth($i)->format('Y-m')] =Subscription::whereHas('adherent',function ($q) use($gym_id){
                $q->where('gym_id',$gym_id);
            })->where(function($q) use($i){
                $q->whereYear('created_at','2021')->whereMonth('created_at',$i);
            })->select('type_subscriptions_id')->pluck('type_subscriptions_id')->toArray();
        }
        foreach ($price_of_months as $key => $value){
            {
                $sum=0;
                if($value==null ||count($value)==0)
                {
                    $price_of_months[$key]=$sum;
                }
                else{
                    foreach ($value as $id)
                    {
                        $sum=$sum+TypeSubscription::find($id)['price'];
                    }
                    $price_of_months[$key]=$sum;
                }
            }
        }
        $stats['curve']['number_subscription_of_day']=$list;
        $stats['curve']['price_of_months']=$price_of_months;
        $res->success($stats);
        return response()->json($res);
    }
}
