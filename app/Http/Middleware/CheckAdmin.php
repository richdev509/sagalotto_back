<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (session('id'))

        {

            if(session('role')=='admin' || Session('role')=='admin2' || Session('role')=='comptable'|| Session('role')=='addeur'){
            return $next($request);
            
            }else{

                return redirect()->route('wplogin');
            }
           
        }else{
            return redirect()->route('wplogin');
        }
        
    }
}
