<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InfoController extends Controller
{

    public function index()
    {
        $res=new Result();
        $query=Gym::query();
        $res->successPaginate($query);
        return response()->json($res);
    }
}
