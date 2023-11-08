<?php

namespace App\Http\Controllers;

use App\DTO\TaskDTO;
use App\Http\Filters\TaskFilter;
use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\DeleteTaskRequest;
use App\Http\Requests\FilterTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskController extends BaseController
{
    public function index(FilterTaskRequest $request): JsonResource
    {
        $data = $request->validated();

        $tasks = $this->service->getAllTasks($data);

        return TaskResource::collection($tasks);
    }

    public function store(CreateTaskRequest $request): TaskResource
    {
        $data = $request->validated();
        $taskDTO = new TaskDTO(...$data);
        $task = $this->service->createTask($taskDTO);
        return new TaskResource($task);
    }

    /**
     * @throws \Exception
     */
    public function update(UpdateTaskRequest $request, int $id): TaskResource
    {
        $data = $request->validated();
        $user_id = $data['user_id'];
        unset($data['user_id']);
        $data['updated_at'] = Carbon::now();
        $taskDTO = new TaskDTO(...$data);
        $task = $this->service->updateTask($taskDTO, $id, $user_id);
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
    public function destroy(DeleteTaskRequest $request, int $id): bool
    {
        $data = $request->validated();
        return $this->service->deleteTask($id, $data['user_id']);
    }
}
