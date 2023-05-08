<?php

namespace App\Filters;

use Carbon\Carbon;

class AgeFilter
{
    function __invoke($query, $age)
    {
        $current = Carbon::now();
        if (strpos($age, ',')) {
            [$min, $max] = explode(",", $age);
        } else {
            $min = $age;
        }
        $min = $current->copy()->subYears($min);
        $max = isset($max) ? $current->copy()->subYears($max) : null;
        if (!$max) {
            return $query->whereYear('birthDate', '=', $min->format('Y'));
        }

        if ($min->gt($max)) {
            return $query->whereBetween('birthDate', [$max->toDateString(), $min->toDateString()]);
        }

        return $query->whereBetween('birthDate', [$min->toDateString(), $max->toDateString()]);
    }
}
