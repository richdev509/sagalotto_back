<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSuperviseur
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        
       // Skip logic for asset paths
       if ($request->is('assets/*') || $request->is('css/*') || $request->is('js/*')) {
        return $next($request);
    }

    // Middleware logic for other routes
    if ($request->is('superviseur/*')) {
        if (session('branchId')) {

            if (session('role') == 'superviseur') {
                return $next($request);
            } else {

                return redirect()->route('suplogin');
            }
        } else {
            return redirect()->route('suplogin');
        }
        // Your middleware logic
    }

    return $next($request);
      

    }
}
