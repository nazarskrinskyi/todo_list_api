<?php

namespace App\Http\Controllers;

use App\DTO\TaskDTO;
use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskController extends BaseController
{
    public function index(): JsonResource
    {
        $tasks = $this->service->getAllTasks();

        return TaskResource::collection($tasks);
    }

    public function store(CreateTaskRequest $request): TaskResource
    {
        $data = $request->validated();
        $taskDTO = new TaskDTO(...$data);
        $task = $this->service->createTask($taskDTO);
        return new TaskResource($task);
    }

    public function update(UpdateTaskRequest $request, int $id): TaskResource
    {
        $data = $request->validated();
        $data['updated_at'] = Carbon::now();
        $taskDTO = new TaskDTO(...$data);
        $task = $this->service->updateTask($taskDTO, $id);
        return new TaskResource($task);
    }

    /**
     * @throws \Exception
     */
    public function markAsDone(int $id): TaskResource
    {
        $task = $this->service->markTaskAsDone($id);
        return new TaskResource($task);
    }

    /**
     * @throws \Exception
     */
    public function destroy(int $id): bool
    {
        return $this->service->deleteTask($id);
    }
}
