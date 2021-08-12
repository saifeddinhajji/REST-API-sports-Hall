<?php

namespace App\Http\Controllers;

use App\Libs\Result;
use App\Models\Activity;
use App\Models\Offer;
use App\Models\User;
use Illuminate\Http\Request;

class EmployeesController extends BaseAuthController
{
    public function index(Request $request)
    {
        $res=new Result();
        $list=User::query();
        if($request->has('role'))
        {

           $list= $list->where('role',$request->role)->where('gym_id',$this->guard()->user()->gym_id)->get();

            $res->success($list);
            return response()->json($res);
        }
        $list->whereIn('role',['coach','secretary'])->where('gym_id',$this->guard()->user()->gym_id);
        $res->successPaginate($list);
        return response()->json($res);
    }
    public function add(Request $request)
    {
        $res = new Result();
        try {
            $user=new User();
            $data=$request->all();
            $data['gym_id']=$this->guard()->user()->gym_id;
            $res=$user->CreateOne($data);
            if(!$res->success)
            {
                throw new \Exception(trans($res->message));
            }
            $res->success();
        }
        catch (\Exception $e) {
            $res->fail($e->getMessage());
            return response()->json($res,400);
        }
        return response()->json($res);
    }
    public function update($id,Request $request)
    {
        $res=new Result();
        $offer=new User();
        try {
            $offer->UpdateOne($request->all(),$id);
            $res->success();
        }
        catch (\Exception $e)
        {
            $res->fail($e->getMessage());
            return response()->json($res,400);
        }
        return response()->json($res);
    }
    public function detail($id)
    {
        $res=new Result();
        $user=User::where('id',$id)->first();
        $res->success($user);
        return response()->json($res);
    }
}
