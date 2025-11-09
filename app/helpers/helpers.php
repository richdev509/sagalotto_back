<?php

if (!function_exists('getTirageColo')) {
    function getTirageColo($tirage)
    {
        $colors = [
            'NewYork Soir' => 'blue',
            'NewYork Matin' => '#06aafd',
            'Florida Matin' => '#53ca8c',
            'Florida Soir' => '#30be64',
            'Georgia Matin' => '#be3030',
            'Georgia ApresMidi' => '#fa8e8e',
        ];
        return $colors[$tirage] ?? 'black';
    }
}