<?php

namespace App\Http\Controllers;

use App\Libs\Result;
use App\Models\Activity;
use App\Models\User;
use Illuminate\Http\Request;

class CoachController extends Controller
{
    public function index()
    {
        $res=new Result();
        $res->success(User::all());
        return response()->json($res);
    }}
