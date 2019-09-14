<?php

namespace App\Http\Middleware;

use Closure;

class TokenAuth
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
        $token=$request->header('X-API-tOKEN');
        if($token != 'value-test')
        {
            abort(401,'auth token not found');
        }
        return $next($request);
    }
}
