<?php

namespace App\Http\Controllers;

use App\Libs\Result;
use App\Models\Gym;
use App\Models\Offer;
use App\Models\User;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    public function index()
    {
        $res=new Result();
        $query=User::where('role','manager');
        $res->successPaginate($query);
        return response()->json($res);
    }
    public function changeStatus($id)
    {
        $res = new Result();
        try {
            $user=User::find($id);
            if(!$user)
            {
                throw new \Exception(trans("manager introuvable dans la base de données"));
            }
            $user['status']==true?$status=false:$status=true;
            $res=$user->UpdateOne(['status'=>$status],$id);
            $res->success();
        }
        catch (\Exception $e) {
            $res->fail($e->getMessage());
            return response()->json($res,400);

        }
        return response()->json($res);
    }
}
