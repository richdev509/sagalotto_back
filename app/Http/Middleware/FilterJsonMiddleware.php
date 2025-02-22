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

        try {
            // Retrieve JSON data from request
            $jsonData = $request->json()->all();
            // Function to filter unique boul1 values
            $filterUniqueBoul1 = function ($items) {
                $seen = [];
                $result = [];

                foreach ($items as $item) {
                    if (isset($item['boul1'], $item['montant'])) {
                        $boul1 = $item['boul1'];
                        $montant = $item['montant'];

                        if (!isset($seen[$boul1])) {
                            // Add the unique 'boul1' to the result
                            $seen[$boul1] = count($result);
                            $result[] = [
                                'boul1' => $boul1,
                                'montant' => $montant
                            ];
                        } else {
                            // Sum the montant for duplicate 'boul1'
                            $result[$seen[$boul1]]['montant'] += $montant;
                        }
                    }
                }

                return $result;
            };
            $filterUniqueLoto45 = function ($items) {
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
                $result = [];

                foreach ($items as $item) {
                    if (isset($item['boul1'], $item['boul2'], $item['montant'])) {
                        $boul1 = $item['boul1'];
                        $boul2 = $item['boul2'];
                        $montant = $item['montant'];
                        
                        $keyParts = [$boul1, $boul2];
                        sort($keyParts); // Ensure a consistent order
                        $key = implode('_', $keyParts);

                        if (!isset($seen[$key])) {
                            // Add the unique 'boul1' to the result
                            $seen[$key] = count($result);

                            $result[] = [
                                'boul1' => $boul1,
                                'boul2' => $boul2,
                                'montant' => $montant
                            ];
                        }else {
                            // Sum the montant for duplicate 'boul1'
                            $result[$seen[$key]]['montant'] += $montant;

                        }
                    }
                }

                return $result;
                

            };

            // Apply filter to each data collection if needed
            foreach ($jsonData as $key => $collection) {
                if ($key === 'maryaj') {
                    $jsonData[$key] = $filterMaryaj($collection);
                } elseif ($key === 'bolete' || $key === 'loto3') {
                    if (is_array($collection)) {
                        $jsonData[$key] = $filterUniqueBoul1($collection);
                    }
                } elseif($key === 'loto4' || $key === 'loto5'){
                    if (is_array($collection)) {
                        $jsonData[$key] = $filterUniqueLoto45($collection);
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
