<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        // Return an array containing the task details
        return [
            'id' => $this->id, // Task ID
            'title' => $this->title, // Task title
            'description' => $this->description, // Task description
            'status' => $this->status, // Task status (todo, done)
            'priority' => $this->priority, // Task priority (1...5)
            'created_at' => $this->created_at, // Task creation timestamp
            'updated_at' => $this->updated_at, // Task last update timestamp
            'completed_at' => $this->completed_at, // Task completion timestamp

            'parent_task' => new TaskResource($this->parentTask),

            // Include subtasks (if any) as an array of TaskResource instances
            'subtasks' => TaskResource::collection($this->subtasks),
        ];
    }
}
