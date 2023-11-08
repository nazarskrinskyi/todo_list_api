<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class TaskFilter extends AbstractFilter
{
    public const TITLE = 'title';
    public const DESCRIPTION = 'description';
    public const STATUS = 'status';
    public const PRIORITY = 'priority';
    public const CREATED_AT = 'created_at';
    public const COMPLETED_AT = 'completed_at';

    protected function getCallbacks(): array
    {
        return [
            self::TITLE => [$this, 'title'],
            self::DESCRIPTION => [$this, 'description'],
            self::STATUS => [$this, 'status'],
            self::PRIORITY => [$this, 'priority'],
            self::CREATED_AT => [$this, 'created_at'],
            self::COMPLETED_AT => [$this, 'completed_at'],
        ];
    }

    public function title(Builder $builder, $value): void
    {
        $builder->where('title', 'like', '%' . $value . '%');
    }

    public function description(Builder $builder, $value): void
    {
        $builder->where('description', 'like', '%' . $value . '%');
    }

    public function status(Builder $builder, $value): void
    {
        $builder->where('status', $value);
    }

    public function priority(Builder $builder, $value): void
    {
        $order = $value === 'desc' ? 'desc' : 'asc';
        $builder->orderBy('priority', $order);
    }

    public function created_at(Builder $builder, $value): void
    {
        $order = $value === 'desc' ? 'desc' : 'asc';
        $builder->orderBy('created_at', $order);
    }

    public function completed_at(Builder $builder, $value): void
    {
        $order = $value === 'desc' ? 'desc' : 'asc';
        $builder->orderBy('completed_at', $order);
    }

}
