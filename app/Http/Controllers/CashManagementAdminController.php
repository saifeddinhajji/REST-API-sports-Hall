<?php

namespace App\Http\Controllers;
use App\Libs\Result;
use App\Models\Gym;
use App\Models\Offer;
use App\Models\SubscriptionGym;
use Illuminate\Http\Request;

class CashManagementAdminController extends Controller
{
    public function index(Request $request)
    {
        $res=new Result();
        $list=SubscriptionGym::query();
        if($request->has('gym_id'))
        {
            $gym_id=$request->get('gym_id');
            $list->whereHas('gym',function($q) use ($gym_id){
               $q->where('id',$gym_id);
           });
        }
        if($request->has('offer_id'))
        {
            $offer_id=$request->get('offer_id');
            $list->whereHas('offer',function($q) use ($offer_id){
                $q->where('id',$offer_id);
            });
        }
        if($request->has('status'))
        {
            $list->where('status',$request->get('status'));
        }
        $res->successPaginate($list);
        return response()->json($res);
    }
    public function changeStatus(Request $request,$id)
    {
        $subGym=new SubscriptionGym();
        $res=$subGym->ChangeStatus($request->all(['status']),$id);
        return response()->json($res);
    }
    public function getSettings()
    {
        $res=new Result();
        $data['list_gyms']=Gym::select('name','id')->get();
        $data['list_offers']=Offer::select(['name','id'])->get();
        $data['cards']['count_subscription_accept']=0;
        $data['cards']['count_subscription_enattente']=0;
        $data['cards']['count_subscription_refuse']=0;
        $data['cards']['count_subscription_profit']=0;
        $res->success($data);
        return response()->json($res);
    }
}
