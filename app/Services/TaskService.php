<?php

namespace App\Services;

use App\DTO\TaskDTO;
use App\Enums\TaskStatusEnum;
use App\Models\Task;
use App\Repositories\TaskRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use function Laravel\Prompts\error;

class TaskService
{
    private TaskRepositoryInterface $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function getAllTasks(): Collection
    {
        return $this->taskRepository->getAllTasks();
    }

    public function createTask(TaskDTO $taskDTO): Task
    {
        return $this->taskRepository->createTask($taskDTO);
    }

    public function updateTask(TaskDTO $taskDTO, int $task_id): Task
    {
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
    public function deleteTask(int $task_id): bool
    {
        try {
            $task = Task::findOrFail($task_id);

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

    public function getTaskTree(int $parentId = null): array
    {
        $tasks = Task::where('parent_id', $parentId)->get();
        $taskTree = [];

        foreach ($tasks as $task) {
            $subtasks = $this->getTaskTree($task->id);
            $taskTree[] = [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'subtasks' => $subtasks,
            ];
        }

        return $taskTree;
    }
}
