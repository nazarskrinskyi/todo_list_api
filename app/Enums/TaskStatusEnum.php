<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self TODO()
 * @method static self DONE()
 */
class TaskStatusEnum extends Enum
{
    protected static function values(): array
    {
        return [
            'TODO' => 'todo',
            'DONE' => 'done',
        ];
    }
}
