<?php

namespace App\Http\Controllers;

use App\Result;
use App\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;

class BaseAuthController extends Controller
{

    public $userId;
    public $user;

    public function __construct()
    {
        try {
            $this->userId = $this->guard()->user()['id'];
            $this->user = $this->guard()->user();
        } catch (\Exception $e) {

        }

    }


    /**
     * Get the guard to be used during authentication.
     *
     * @return Guard
     */
    public function guard()
    {
        return Auth::guard('api');
    }


    /**
     * Get the authenticated User
     *
     * @return Result
     */
    public function user()
    {
        $res = new Result();
        try {
            $res->success($this->guard()->user());
        } catch (\Exception $e) {
            $res->fail('Unauthorized');
        }
        return $res;
    }

}
