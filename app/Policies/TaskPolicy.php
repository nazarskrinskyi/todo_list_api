<?php

namespace App\Policies;

use App\Enums\TaskStatusEnum;
use App\Models\User;
use App\Models\Task;

class TaskPolicy
{
    public function update(User $user, Task $task): bool
    {
        // User can only update their own tasks
        dd($user);
        return $user->id === $task->user_id;
    }

    public function delete(User $user, Task $task): bool
    {
        // User can only delete their own tasks
        return $user->id === $task->user_id && $task->status !== TaskStatusEnum::DONE;
    }

    public function markAsCompleted(User $user, Task $task): bool
    {
        // User can mark a task as completed only if it has no incomplete subtasks
        return $user->id === $task->user_id && !$task->hasIncompleteSubtasks();
    }
}
