<?php

namespace App\Repositories;

use App\DTO\TaskDTO;
use App\Enums\TaskStatusEnum;
use App\Http\Filters\TaskFilter;
use App\Models\Task;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

/**
 * TaskRepository class handles the data interaction for tasks.
 */
class TaskRepository implements TaskRepositoryInterface
{

    /**
     * Get all tasks from the database.
     *
     * @param array $data
     * @return Collection
     * @throws BindingResolutionException
     */
    public function getFilteredTasks(array $data): Collection
    {
        $filter = app()->make(TaskFilter::class, ['queryParams' => array_filter($data)]);
        $filterQuery = Task::filter($filter);

        return $filterQuery->get();
    }

    public function getTaskById(int $task_id): Task
    {
        // Retrieve tasks from the 'tasks' table
        return Task::findOrFail($task_id);
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
        $task->user_id = $taskDTO->user_id ?? 1;
        $task->priority = $taskDTO->priority;
        $task->status = $taskDTO->status;

        $task->save();

        return $task;
    }
    public function hasUncompletedSubtasks(Task $task): bool
    {
        // Recursively check if the task or any of its subtasks have uncompleted status
        foreach ($task->subtasks as $subtask) {
            if ($subtask->status !== TaskStatusEnum::DONE || $this->hasUncompletedSubtasks($subtask)) {
                return true;
            }
        }

        // If no uncompleted subtasks found, return false
        return false;
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
        $task->parent_id = $taskDTO->parent_id;
        $task->priority = $taskDTO->priority;
        $task->status = $taskDTO->status;
        $task->updated_at = $taskDTO->updated_at;

        // Save the updated task to the database
        $task->save();

        // Return the updated task instance
        return $task;
    }
}
