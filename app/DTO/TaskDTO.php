<?php

namespace App\DTO;

use App\Models\Task;

class TaskDTO
{
    public function __construct(
        public ?int $id,
        public string $title,
        public string $user_id,
        public string $parent_id,
        public string $description,
        public string $status,
        public int $priority,
        public ?string $created_at,
        public ?string $updated_at,
        public ?string $completed_at,
    ) {}

    public static function fromModel(Task $task): self
    {
        return new self(
            id: $task->id,
            title: $task->title,
            user_id: $task->user_id,
            parent_id: $task->parent_id ?? null,
            description: $task->description ?? null,
            status: $task->status,
            priority: $task->priority,
            created_at: $task->created_at ? $task->created_at->format('Y-m-d H:i:s') : null,
            updated_at: $task->updated_at ? $task->updated_at->format('Y-m-d H:i:s') : null,
            completed_at: $task->completed_at ? $task->completed_at->format('Y-m-d H:i:s') : null
        );
    }
}
