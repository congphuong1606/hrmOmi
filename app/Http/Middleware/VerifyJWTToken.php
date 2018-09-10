<?php

namespace App\Http\Middleware;

use App\Helpers\ApiHelper;
use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;

class VerifyJWTToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $user = \JWTAuth::parseToken()->authenticate();
        }catch (JWTException $e) {
            if($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return ApiHelper::responseFail('token_expired',[],401);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return ApiHelper::responseFail('token_invalid',[],401);
            }else{
                return ApiHelper::responseFail('Token is required',[],401);
            }
        }
        return $next($request);
    }
}
