<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

/**
 * AbstractFilter class provides a base implementation for filter classes.
 */
abstract class AbstractFilter implements FilterInterface
{
    /** @var array **/
    protected array $queryParams = [];

    /**
     * Constructor to initialize the query parameters.
     *
     * @param array $queryParams
     */
    public function __construct(array $queryParams)
    {
        $this->queryParams = $queryParams;
    }

    /**
     * Abstract method to be implemented by subclasses.
     * Returns an associative array of filter names and their corresponding callback methods.
     *
     * @return array
     */
    abstract protected function getCallbacks(): array;

    /**
     * Applies the filters on the given builder instance.
     *
     * @param Builder $builder
     */
    public function apply(Builder $builder): void
    {
        // Iterate through defined filter callbacks and apply filters if query parameters are present
        foreach ($this->getCallbacks() as $name => $callback) {
            if (isset($this->queryParams[$name])) {
                // Call the corresponding filter callback with the builder and query parameter value
                call_user_func($callback, $builder, $this->queryParams[$name]);
            }
        }
    }
}
