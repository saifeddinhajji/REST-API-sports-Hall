<?php
namespace App\Http\Controllers;
use App\Libs\Result;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class ActivityController extends BaseAuthController
{
    public function index(Request $request)
    {
        ///
        $res=new Result();
        $list=Activity::query();
        $gym_id=$this->guard()->user()->gym_id;
        if($request->has('list'))
        {
                $list= $list->where('status',1)->whereHas('coach',function ($q) use($gym_id){
                $q->where('gym_id',$gym_id);
            })->get();
            $res->success($list);
            return response()->json($res);
        }
        $list->whereHas('coach',function ($q) use ($gym_id)
        {
            $q->where('gym_id',$gym_id);
        })->get();
        $res->successPaginate($list);
        return response()->json($res);
    }
    public function add(Request $request)
    {
        $activity=new Activity();
        $res= $activity->CreateOne($request->all());
        if(!$res->success)
        {
            return response()->json($res,400);
        }
        return response()->json($res);
    }
    public function update($id,Request $request)
    {
        $res=new Result();
        try {
            $activity=new Activity();
            if($request->has('status'))
            {
                $activity->roleDataCreate=[];
            }
            $res=$activity->UpdateOne($request->all(),$id);
        }
        catch (\Exception $e)
        {
            $res->fail($e->getMessage());
        }
        return response()->json($res);
    }
    //

}
