<?php

namespace App\Http\Controllers;

use App\Libs\Result;
use App\Models\Activity;
use App\Models\Offer;
use Egulias\EmailValidator\Warning\ObsoleteDTEXT;
use Illuminate\Http\Request;

class OfferController extends Controller
{

    public function index(Request $request)
    {
        $res=new Result();
        $query=Offer::where(function ($q) use($request)
        {
            if($request->get('status'))
            {
                $q->where('status',$request->get('status'));
            }
        })->get();
        $res->success($query);
        return response()->json($res);
    }

    public function changeStatus($id)
    {
        $res = new Result();
        try {
        $offer=Offer::find($id);
            if(!$offer)
            {
                throw new \Exception(trans("offre introuvable dans la base de données"));
            }
        $offer['status']==true?$status=false:$status=true;
        $res=$offer->UpdateOne(['status'=>$status],$id);
        $res->success();
        }
        catch (\Exception $e) {
            $res->fail($e->getMessage());
            return response()->json($res,400);

        }
        return response()->json($res);
    }
    public function add(Request $request)
    {
        $res = new Result();
        try {
            $offer=new Offer();
            $res=$offer->CreateOne($request->all(['name','duration','price','unit']));
            if(!$res->success)
            {
                throw new \Exception(trans($res->message));
            }
            $res->success();
        }
        catch (\Exception $e) {
            $res->fail($e->getMessage());
        }
        return response()->json($res);
    }

    public function delete(int $id)
    {
        $res=new Result();
        Offer::destroy($id);
        $res->success();
        return response()->json($res);
    }

    public function update($id,Request $request)
    {
        $res=new Result();
        $offer=new Offer();
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
}
