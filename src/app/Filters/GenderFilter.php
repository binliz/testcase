<?php

namespace App\Filters;

class GenderFilter
{
    function __invoke($query, $genderName)
    {
        return $query->whereHas('gender', function ($query) use ($genderName) {
            $genderNames = explode(',', $genderName);
            if (count($genderNames) > 1) {
                return $query->whereIn('name', $genderNames);
            }
            if (is_array($genderName)) {
                return $query->whereIn('name', $genderName);
            }
            return $query->where('name', $genderName);
        });
    }
}
