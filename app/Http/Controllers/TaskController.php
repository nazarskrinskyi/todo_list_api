<?php

namespace App\Http\Controllers;

use App\DTO\TaskDTO;
use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\FilterTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

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
        $task = Task::findOrFail($id);
        if ($id !== $task->user_id) {
            $this->authorize('update', $task);
        }

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
     * @throws \Exception
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
     * @throws \Exception
     */
    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        $task = Task::findOrFail($id);
        if ($id !== $task->user_id) {
            $this->authorize('delete', $task);
        }

        return $this->service->deleteTask($id);
    }
}

