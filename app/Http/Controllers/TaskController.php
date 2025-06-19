<?php

namespace App\Http\Controllers;

use App\DTO\TaskDTO;
use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\FilterTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use Carbon\Carbon;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\JsonResponse;

class TaskController extends BaseController
{
    /**
     * Get a list of tasks based on the provided filters.
     *
     * @param FilterTaskRequest $request
     * @return JsonResource
     */
    public function index(FilterTaskRequest $request): JsonResource
    {
        $data = $request->validated();

        $tasks = $this->service->getAllTasks($data);

        return TaskResource::collection($tasks)->additional([
            'tasks' => TaskResource::collection($tasks)
        ]);
    }

    /**
     * Store a new task.
     *
     * @param CreateTaskRequest $request
     * @return TaskResource
     */
    public function store(CreateTaskRequest $request): TaskResource
    {
        $data = $request->validated();

        $taskDTO = new TaskDTO(...$data);

        $task = $this->service->createTask($taskDTO);

        return new TaskResource($task);
    }

    /**
     * Update an existing task.
     *
     * @param UpdateTaskRequest $request
     * @param int $id
     * @return TaskResource
     * @throws \Exception
     */
    public function update(UpdateTaskRequest $request, int $id): TaskResource
    {
        $data = $request->validated();
        $data['updated_at'] = Carbon::now();
        $taskDTO = new TaskDTO(...$data);

        $task = $this->service->updateTask($taskDTO, $id, $taskDTO->user_id);

        return new TaskResource($task);
    }

    /**
     * Mark a task as done.
     *
     * @param int $id
     * @return TaskResource
     * @throws Exception
     */
    public function markAsDone(int $id): TaskResource
    {
        $task = $this->service->markTaskAsDone($id);

        return new TaskResource($task);
    }

    /**
     * Delete a task.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws AuthorizationException
     * @throws Exception
     */
    public function destroy(int $id): JsonResponse
    {
        return $this->service->deleteTask($id);
    }
}

