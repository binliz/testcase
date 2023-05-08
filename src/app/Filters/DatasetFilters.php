<?php

namespace App\Filters;

use Illuminate\Http\Request;

class DatasetFilters
{
    private $request;

    public function __construct(?Request $request)
    {
        if ($request) {
            $this->request = $request;
        } else {
            $this->request = request();
        }
    }

    protected array $filters = [
        'category' => CategoryFilter::class,
        'gender' => GenderFilter::class,
        'age' => AgeFilter::class,
        'birthDate' => BirthDateFilter::class
    ];

    public function apply($query)
    {
        foreach ($this->receivedFilters() as $name => $value) {
            $filterInstance = new $this->filters[$name];
            $query = $filterInstance($query, $value);
        }

        return $query;
    }

    public function receivedFilters(): array
    {
        return $this->request->only(array_keys($this->filters));
    }
}
