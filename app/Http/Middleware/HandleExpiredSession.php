<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleExpiredSession
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($response->status() === 419) {
            return redirect('/');
        }

        return $response;
    }
}

