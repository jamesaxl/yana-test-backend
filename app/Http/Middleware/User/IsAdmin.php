<?php

namespace App\Http\Middleware\User;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ('admin' != auth()->user()->role->name) {
            return response()->json(
                [
                    'error' => 1,
                    'message' => 'permission denied'
                ]
                , 401
            );
        }
        return $next($request);
    }
}
