<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

/**
 * FilterInterface defines the contract for filter classes.
 */
interface FilterInterface
{
    /**
     * Applies the filters on the given builder instance.
     *
     * @param Builder $builder
     */
    public function apply(Builder $builder);
}
