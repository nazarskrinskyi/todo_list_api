<?php

namespace App\Services;

use App\DTO\TaskDTO;
use App\Enums\TaskStatusEnum;
use App\Http\Filters\TaskFilter;
use App\Models\Task;
use App\Repositories\TaskRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * TaskService class contains business logic for managing tasks.
 */
class TaskService
{
    private TaskRepositoryInterface $taskRepository;

    /**
     * TaskService constructor.
     *
     * @param TaskRepositoryInterface $taskRepository
     */
    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * Get filtered tasks based on the provided data.
     *
     * @param array $data
     * @return Collection
     */
    public function getAllTasks($data): Collection
    {
        // Create a TaskFilter instance with filtered query parameters
        $filter = app()->make(TaskFilter::class, ['queryParams' => array_filter($data)]);
        // Apply filters to Task model using the TaskFilter instance
        $filter_query = Task::filter($filter);
        // Retrieve filtered tasks
        return $filter_query->get();
    }

    /**
     * Create a new task based on the provided TaskDTO instance.
     *
     * @param TaskDTO $taskDTO
     * @return Task
     */
    public function createTask(TaskDTO $taskDTO): Task
    {
        // Create a new task using the TaskRepository
        return $this->taskRepository->createTask($taskDTO);
    }

    /**
     * Update an existing task based on the provided TaskDTO instance, task ID, and user ID.
     *
     * @param TaskDTO $taskDTO
     * @param int $task_id
     * @param int $user_id
     * @return Task
     * @throws \Exception
     */
    public function updateTask(TaskDTO $taskDTO, int $task_id, int $user_id): Task
    {
        // Find the task by its ID
        $task = Task::findOrFail($task_id);

        // Check if the task belongs to the user
        if ($task->user_id !== $user_id) {
            throw new \Exception("You don't have permission to update this task.");
        }

        // Update the task using the TaskRepository
        return $this->taskRepository->updateTask($taskDTO, $task_id);
    }

    /**
     * Mark a task as done only if it has no uncompleted subtasks.
     *
     * @param int $task_id
     * @return Task|bool
     * @throws \Exception
     */
    public function markTaskAsDone(int $task_id): Task|bool
    {
        // Find the task by its ID
        try {
            $task = Task::findOrFail($task_id);

            // Check if the task has uncompleted subtasks
            if ($this->hasUncompletedSubtasks($task)) {
                throw new \Exception("Task has uncompleted subtasks and cannot be marked as done.");
            }

            // Mark the task as done and update completion timestamp
            $task->status = TaskStatusEnum::DONE;
            $task->completed_at = Carbon::now();
            $task->save();

            // Return the updated task
            return $task;
        } catch (ModelNotFoundException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Check if the task has uncompleted subtasks.
     *
     * @param Task $task
     * @return bool
     */
    private function hasUncompletedSubtasks(Task $task): bool
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
     * Delete a task if it's not done, otherwise throw an exception.
     *
     * @param int $task_id
     * @param int $user_id
     * @return bool
     * @throws \Exception
     */
    public function deleteTask(int $task_id, int $user_id): bool
    {
        // Find the task by its ID
        try {
            $task = Task::findOrFail($task_id);

            // Check if the task belongs to the user
            if ($task->user_id !== $user_id) {
                throw new \Exception("You don't have permission to delete this task.");
            }

            // Check if the task is not done
            if ($task->status !== TaskStatusEnum::DONE) {
                // Delete the task
                $task->delete();
                return true;
            } else {
                // If task is done, throw an exception
                throw new \Exception("This task is done and cannot be deleted.");
            }
        } catch (ModelNotFoundException $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
