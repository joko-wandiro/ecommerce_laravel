<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Route;
use Gate;
use Firebase\JWT\JWT;

//use Illuminate\Routing\Router as Route;

class JWTAuthentication
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
        $token = request()->bearerToken();
        if (!$token) {
            abort(403);
        }
        $parameters = JWT::decode($token, config('auth.jwt_key'), array('HS256'));
        if ($parameters->id) {
            return $next($request);
        }
    }

}
