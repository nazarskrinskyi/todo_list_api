<?php

namespace App\Http\Controllers;

use App\DTO\TaskDTO;
use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\DeleteTaskRequest;
use App\Http\Requests\FilterTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
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

        // Get tasks based on the provided filters
        $tasks = $this->service->getAllTasks($data);

        // Return the tasks as a JSON resource
        return TaskResource::collection($tasks);
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

        // Create a new task using the provided data
        $task = $this->service->createTask($taskDTO);

        // Return the created task as a JSON resource
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
        $user_id = $data['user_id'];
        unset($data['user_id']);
        $data['updated_at'] = Carbon::now();
        $taskDTO = new TaskDTO(...$data);

        // Update the existing task with the provided data
        $task = $this->service->updateTask($taskDTO, $id, $user_id);

        // Return the updated task as a JSON resource
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
        // Mark the task with the given ID as done
        $task = $this->service->markTaskAsDone($id);

        // Return the updated task as a JSON resource
        return new TaskResource($task);
    }

    /**
     * Delete a task.
     *
     * @param DeleteTaskRequest $request
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function destroy(DeleteTaskRequest $request, int $id): bool
    {
        $data = $request->validated();

        // Delete the task with the given ID
        return $this->service->deleteTask($id, $data['user_id']);
    }
}

