<?php

namespace App\Http\Middleware\User;

use Closure;
use Illuminate\Http\Request;

class IsEnabled
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
        if (false === auth()->user()->is_enabled) {
            return response()->json(
                [
                    'error' => 1,
                    'message' => 'account not enabled'
                ]
                , 401
            );
        }
        return $next($request);
    }
}
