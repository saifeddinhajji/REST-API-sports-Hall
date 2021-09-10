<?php

namespace App\Http\Controllers;

use App\Libs\Result;
use App\Models\Offer;
use App\Models\SubscriptionGym;
use Illuminate\Http\Request;

class RenewalController extends BaseAuthController
{

public function list()
{
    $res=new Result();
    $gym_id=$this->guard()->user()->gym_id;
    $query=SubscriptionGym::where('gym_id',$gym_id);
    $res->successPaginate($query);
    return response()->json($res);
}

    public function add(Request $request)
    {
        $gym_id=$this->guard()->user()->gym_id;
        $res=new Result();
            try {
                $sub=SubscriptionGym::where('gym_id',$gym_id)->where('status','accepter')->orderby('id','DESC')->first();
                $dataSubscription = $request->all(['start_at','end_at','offer_id','payment_receipt']);

                if(($sub && $sub['end_at']>$dataSubscription['start_at']) ||($sub && $sub['end_at']> $dataSubscription['end_at']))
                    throw new \Exception('Vous avez un abonnement existant');
                $dataSubscription['gym_id']=$gym_id;
                $subscriptionGym=new SubscriptionGym();
                $res= $subscriptionGym->CreateOne($dataSubscription);
            }
        catch (\Exception $e)
        {
            $res->fail($e->getMessage());
        }

        return response()->json($res);
    }
}
