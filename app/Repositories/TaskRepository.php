<?php

namespace App\Repositories;

use App\DTO\TaskDTO;
use App\Enums\TaskStatusEnum;
use App\Models\Task;

/**
 * TaskRepository class handles the data interaction for tasks.
 */
class TaskRepository implements TaskRepositoryInterface
{
    /**
     * Get all tasks from the database.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllTasks(): \Illuminate\Database\Eloquent\Collection
    {
        // Retrieve all tasks from the 'tasks' table
        return Task::all();
    }

    /**
     * Create a new task in the database based on the provided TaskDTO instance.
     *
     * @param TaskDTO $taskDTO
     * @return Task
     */
    public function createTask(TaskDTO $taskDTO): Task
    {
        // Create a new Task instance with data from TaskDTO
        $task = new Task();
        $task->title = $taskDTO->title;
        $task->description = $taskDTO->description;
        $task->parent_id = $taskDTO->parent_id;
        $task->user_id = $taskDTO->user_id;
        $task->priority = $taskDTO->priority;
        $task->status = $taskDTO->status;

        // Save the task to the database
        $task->save();

        // Return the created task instance
        return $task;
    }

    /**
     * Update an existing task in the database based on the provided TaskDTO instance and task ID.
     *
     * @param TaskDTO $taskDTO
     * @param int $task_id
     * @return Task
     */
    public function updateTask(TaskDTO $taskDTO, int $task_id): Task
    {
        // Find the task by its ID
        $task = Task::findOrFail($task_id);

        // Update task attributes with data from TaskDTO
        $task->title = $taskDTO->title;
        $task->description = $taskDTO->description;
        $task->priority = $taskDTO->priority;
        $task->status = $taskDTO->status;
        $task->updated_at = $taskDTO->updated_at;

        // Save the updated task to the database
        $task->save();

        // Return the updated task instance
        return $task;
    }
}
