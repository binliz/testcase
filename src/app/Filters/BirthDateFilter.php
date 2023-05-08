<?php

namespace App\Filters;

use Carbon\Carbon;

class BirthDateFilter
{
    function __invoke($query, $birthDate)
    {
        return $query->where('birthDate', $birthDate);
    }
}
