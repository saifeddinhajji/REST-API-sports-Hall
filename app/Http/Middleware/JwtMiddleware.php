<?php

namespace App\Http\Middleware;

use App\Libs\Result;
use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $res = new Result();
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!isset($user['id'])) {
                throw new \Exception("failed");
            }
        } catch (\Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException ) {
                $res->fail('Token is Invalid');
                return response()->json($res,401);
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                $res->fail('Token is Expired');
                return response()->json($res,403);
            } else {
                $res->fail('Authorization Token not found');
                return response()->json($res);
            }
        }
        return $next($request);
    }
}
