<?php

namespace App\Http\Controllers;

use App\Libs\Result;
use App\Models\User;
use Illuminate\Http\Request;

class AdherentController extends BaseAuthController
{
    public function index(Request $request)
    {
        $res=new Result();
        $list=User::query();
        if($request->has('list'))
        {
            $list= $list->where('role','adherent')->where('gym_id',$this->guard()->user()->gym_id)->latest()->get();
            $res->success($list);
            return response()->json($res);
        }
        $list=$list->where('role','adherent')->where('gym_id',$this->guard()->user()->gym_id)->latest();
        $res->successPaginate($list->latest());
        return response()->json($res);
    }
    public function add(Request $request)
    {
            $user=new User();
            $data=$request->all();
            $data['gym_id']=$this->guard()->user()->gym_id;
            $res=$user->CreateOne($data);
            return response()->json($res);
    }
    public function update($id,Request $request)
    {
        $res=new Result();
        $adherent=new User();
        $adherent->roleDataCreate=[];
        try {
          $res=  $adherent->UpdateOne($request->all(),$id);
        }
        catch (\Exception $e)
        {
            $res->fail($e->getMessage());
            return response()->json($res,400);
        }
        return response()->json($res);
    }
}
