<?php

namespace App\Http\Controllers;

use App\Libs\Result;
use App\Models\Subscription;
use App\Models\TypeSubscription;
use Illuminate\Http\Request;

class TypeSubscriptionController extends Controller
{
    public function index()
    {
        $res=new Result();
        $res->success(TypeSubscription::all());
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
