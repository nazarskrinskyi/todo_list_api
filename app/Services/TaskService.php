<?php

namespace App\Services;

use App\DTO\TaskDTO;
use App\Enums\TaskStatusEnum;
use App\Models\Task;
use App\Repositories\TaskRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class TaskService
{
    private TaskRepositoryInterface $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function getAll(): Collection
    {
        return $this->taskRepository->getAllTasks();
    }

    public function create(TaskDTO $taskDTO): Task
    {
        return $this->taskRepository->createTask($taskDTO);
    }

    public function update(TaskDTO $taskDTO): Task
    {
        return $this->taskRepository->updateTask($taskDTO);
    }

    public function markAsDone(int $task_id): Task
    {
        $task = Task::findOrFail($task_id);
        $task->status = TaskStatusEnum::DONE;
        $task->save();

        return $task;
    }

    public function delete(int $task_id): bool
    {
        $task = Task::findOrFail($task_id);

        if ($task) return true;

        else return false;
    }


}