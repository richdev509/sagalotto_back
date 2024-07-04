<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FilterJsonMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
           try{
           // Retrieve JSON data from request
           $jsonData = $request->json()->all();
           // Function to filter unique boul1 values
           $filterUniqueBoul1 = function ($items) {
               $seen = [];
               return array_values(array_filter($items, function ($item) use (&$seen) {
                   if (isset($item['boul1']) && !in_array($item['boul1'], $seen)) {
                       $seen[] = $item['boul1'];
                       return true;
                   }
                   return false;
               }));
           };
           $filterMaryaj = function ($items) {
               $seen = [];
               return array_values(array_filter($items, function ($item) use (&$seen) {
                   $identifier1 = $item['boul1'] . '-' . $item['boul2'];
                   $identifier2 = $item['boul2'] . '-' . $item['boul1'];
                   if (isset($item['boul1']) && isset($item['boul2']) && !in_array($identifier1, $seen) && !in_array($identifier2, $seen)) {
                       $seen[] = $identifier1;
                       $seen[] = $identifier2;
                       return true;
                   }
                   return false;
               }));
           };
   
           // Apply filter to each data collection if needed
           foreach ($jsonData as $key => $collection) {
               if ($key === 'maryaj') {
                   $jsonData[$key] = $filterMaryaj($collection);
               } elseif($key === 'bolete' || $key === 'loto3' || $key === 'loto4' || $key === 'loto5') {
                   if (is_array($collection)) {
                       $jsonData[$key] = $filterUniqueBoul1($collection);
                   }
               }else{
                  $jsonData[$key] = $collection;
               }
           }
          
           $request->replace($jsonData);
           return   $next($request);
        } catch (\Exception $e) {
            // Handle any errors gracefully
            return response()->json(['error' => 'An error occurred while processing the data.'], 500);
        }

      
    }
}
