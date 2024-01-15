<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
//use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;


class CheckBeforeAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $current_url = Route::current()->uri();
       
        if (strpos($current_url, 'login')== true) {
            return $next($request);
        } else {
            if (!auth()->user()) {
                return response()->json([
                    'status' => 'false',
                    'message'=> 'Unauthorized',
                    'code' =>'401'
        
                ], 401);
            }
            return $next($request); // Proceed if condition is met

        }
       
    }
}
