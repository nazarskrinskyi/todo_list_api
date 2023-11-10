<?php

namespace App\Repositories;

use App\DTO\TaskDTO;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

interface TaskRepositoryInterface
{
    public function getFilteredTasks(array $data): Collection;
    public function getTaskById(int $task_id): Task;

    public function createTask(TaskDTO $taskDTO): Task;

    public function updateTask(TaskDTO $taskDTO, int $task_id): Task;

}