<?php

namespace App\Enums;

class TaskStatusEnum
{
    public const TODO = 'todo';
    public const DONE = 'done';

    public static function values(): array
    {
        return [
            self::TODO,
            self::DONE,
        ];
    }
}
