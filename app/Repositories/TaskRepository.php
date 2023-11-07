<?php

namespace App\Repositories;

use App\DTO\TaskDTO;
use App\Enums\TaskStatusEnum;
use App\Models\Task;

class TaskRepository implements TaskRepositoryInterface
{

    public function getAllTasks(): \Illuminate\Database\Eloquent\Collection
    {
        return Task::all();
    }

    public function createTask(TaskDTO $taskDTO): Task
    {
        $task = new Task();
        $task->id = $taskDTO->id;
        $task->title = $taskDTO->title;
        $task->description = $taskDTO->description;
        $task->parent_id = $taskDTO->parent_id;
        $task->user_id = $taskDTO->user_id;
        $task->priority = $taskDTO->priority;
        $task->status = $taskDTO->status;
        $task->created_at = $taskDTO->created_at;
        $task->completed_at = $taskDTO->completed_at;
        $task->updated_at = $taskDTO->updated_at;

        $task->save();

        return $task;
    }

    public function updateTask(TaskDTO $taskDTO): Task
    {
        $task = Task::findOrFail($taskDTO->id);

        $task->title = $taskDTO->title;
        $task->description = $taskDTO->description;
        $task->parent_id = $taskDTO->parent_id;
        $task->priority = $taskDTO->priority;
        $task->status = $taskDTO->status;
        $task->updated_at = $taskDTO->updated_at;


        $task->save();

        return $task;
    }


}