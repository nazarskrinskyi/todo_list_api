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

class TaskService
{
    private TaskRepositoryInterface $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function getAllTasks($data): Collection
    {
        $filter = app()->make(TaskFilter::class, ['queryParams' => array_filter($data)]);
        $filter_query = Task::filter($filter);
        return $filter_query->get();
    }

    public function createTask(TaskDTO $taskDTO): Task
    {
        return $this->taskRepository->createTask($taskDTO);
    }

    /**
     * @throws \Exception
     */
    public function updateTask(TaskDTO $taskDTO, int $task_id, int $user_id): Task
    {
        $task = Task::findOrFail($task_id);
        // Перевірка чи завдання належить користувачеві
        if ($task->user_id !== $user_id) {
            throw new \Exception("You don't have permission to update this task.");
        }
        return $this->taskRepository->updateTask($taskDTO, $task_id);
    }

    /**
     * Mark task as done only if it has no uncompleted subtasks.
     *
     * @param int $task_id
     * @return Task|bool
     * @throws \Exception
     */
    public function markTaskAsDone(int $task_id): Task|bool
    {
        try {
            $task = Task::findOrFail($task_id);

            if ($this->hasUncompletedSubtasks($task)) {
                throw new \Exception("Task has uncompleted subtasks and cannot be marked as done.");
            }

            $task->status = TaskStatusEnum::DONE;
            $task->completed_at = Carbon::now();
            $task->save();

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
        foreach ($task->subtasks as $subtask) {
            if ($subtask->status !== TaskStatusEnum::DONE) {
                return true;
            }

            if ($this->hasUncompletedSubtasks($subtask)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Delete task if it's not done, otherwise throw an exception.
     *
     * @param int $task_id
     * @return bool
     * @throws \Exception
     */
    public function deleteTask(int $task_id, int $user_id): bool
    {
        try {
            $task = Task::findOrFail($task_id);

            // Перевірка чи завдання належить користувачеві
            if ($task->user_id !== $user_id) {
                throw new \Exception("You don't have permission to delete this task.");
            }

            if ($task->status !== TaskStatusEnum::DONE) {
                $task->delete();
                return true;
            } else {
                throw new \Exception("This task is done and can not be deleted.");
            }
        } catch (ModelNotFoundException $e) {
            throw new \Exception($e->getMessage());
        }
    }

}
