<?php

namespace App\Http\Controllers;

use App\Libs\Result;
use App\Models\Gym;

class GymsController extends Controller
{
    public function index()
    {
        $res=new Result();
        $query=Gym::query();
        $res->successPaginate($query);
        return response()->json($res);
    }
}
