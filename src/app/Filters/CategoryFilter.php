<?php

namespace App\Filters;

class CategoryFilter
{
    function __invoke($query, $categoryName)
    {
        return $query->whereHas('category', function ($query) use ($categoryName) {
            $categoryNames = explode(',', $categoryName);
            if (count($categoryNames) > 1) {
                return $query->whereIn('name', $categoryNames);
            }

            if (is_array($categoryName)) {
                return $query->whereIn('name', $categoryName);
            }

            return $query->where('name', $categoryName);
        });
    }
}
