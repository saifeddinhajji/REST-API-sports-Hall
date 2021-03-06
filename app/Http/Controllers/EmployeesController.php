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
           $list= $list->where('role',$request->role)->where('gym_id',$this->guard()->user()->gym_id);
            $res->successPaginate($list);
            return response()->json($res);
        }
        $list->whereIn('role',['coach','secretary'])->where('gym_id',$this->guard()->user()->gym_id);
        $res->successPaginate($list->latest());
        return response()->json($res);
    }
    public function listCoachs()
    {
        $res=new Result();
        $list= User::where('role','coach')->where('gym_id',$this->guard()->user()->gym_id)->latest()->get();
        $res->success($list);
        return response()->json($res);
    }
    public function add(Request $request)
    {
        $res = new Result();
        try {
            $user=new User();
            $data=$request->all();
            $data['gym_id']=$this->guard()->user()->gym_id;
            $data['status']=true;
            $res=$user->CreateOne($data);
            if(!$res->success) {  throw new \Exception(trans($res->message)); }
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
        $user=new User();
        try {
           $res= $user->UpdateOne($request->except('phone','email'),$id);
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
