<?php

namespace App\Http\Controllers;

use App\Libs\Result;
use App\Models\Subscription;
use App\Models\TypeSubscription;
use Illuminate\Database\Console\Migrations\BaseCommand;
use Illuminate\Http\Request;

class TypeSubscriptionController extends BaseAuthController
{
    public function index()
    {
        $res=new Result();
        $gym_id=$this->guard()->user()->gym_id;
        $list=TypeSubscription::whereHas('activity',function($q) use ($gym_id) {
            $q->whereHas('coach', function ($q) use ($gym_id) { $q->where('gym_id', $gym_id); });
        });
        $res->successPaginate($list);
        return response()->json($res);
    }

    public function add(Request $request)
    {
        $typeSubscription=new TypeSubscription();
        $res= $typeSubscription->CreateOne($request->all());
        return response()->json($res);
    }

    public function delete(int $id)
    {
        $res=new Result();
        TypeSubscription::destroy($id);
        $res->success();
        return response()->json($res);
    }

    public function update($id,Request $request)
    {
        $res=new Result();
        $TypeSubscription=new TypeSubscription();
        $TypeSubscription->UpdateOne($request->all(),$id);
        $res->success();
        return response()->json($res);
    }
}
