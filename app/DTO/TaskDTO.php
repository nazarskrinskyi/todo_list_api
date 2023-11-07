<?php

namespace App\DTO;

use App\Enums\TaskStatusEnum;
use App\Models\Task;

class TaskDTO
{
    public function __construct(
        public string $title,
        public string $description,
        public int $priority,
        public string $status,
        public ?int $parent_id = null,
        public ?int $user_id = null,
        public ?string $updated_at = null,
    ) {}

    public static function fromModel(Task $task): self
    {
        return new self(
            title: $task->title,
            description: $task->description,
            priority: $task->priority,
            status: $task->status,
            parent_id: $task->parent_id,
            user_id: $task->user_id,
            updated_at: $task->updated_at,
        );
    }
}
