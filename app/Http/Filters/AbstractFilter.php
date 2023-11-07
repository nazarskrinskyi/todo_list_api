<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;


abstract class AbstractFilter implements FilterInterface
{
    /** @var array **/
    protected array $queryParams = [];

    /**
     * @param array $queryParams
     */
    public function __construct(array $queryParams)
    {
        $this->queryParams = $queryParams;
    }

    abstract protected function getCallbacks(): array;
    /**
     * @param Builder $builder
     */

    public function apply(Builder $builder): void
    {
        foreach ($this->getCallbacks() as $name => $callback)
        {
            if (isset($this->queryParams[$name])) {
                call_user_func($callback, $builder, $this->queryParams[$name]);
            }
        }
    }


}
