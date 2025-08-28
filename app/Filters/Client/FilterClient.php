<?php

namespace App\Filters\Client;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FilterClient implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->where(function ($query) use ($value) {
            $query->where('name', 'like', '%' . $value . '%')
            ->orWhereHas('phones', function ($q) use ($value) {
               $q->where('phone', 'like', '%' . $value . '%');
            })
            ->orWhereHas('emails', function ($q) use ($value) {
                $q->where('email', 'like', '%' . $value . '%');
             });

        });
    }
}
