<?php

namespace App\Models;

use App\Enums\TaskStatusEnum;
use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Task Model represents individual tasks in the application.
 *
 * @method static find(mixed $taskId) - Find a task by its ID.
 * @method static findOrFail(mixed $taskId) - Find a task by its ID or throw an exception if not found.
 * @property mixed $user_id - User ID associated with the task.
 */
class Task extends Model
{
    use HasFactory, Filterable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', // Task title
        'description', // Task description
        'status', // Task status (todo, done)
        'priority', // Task priority (1...5)
        'created_at', // Task creation timestamp
        'updated_at', // Task last update timestamp
        'completed_at' // Task completion timestamp
    ];

    /**
     * Disable mass assignment protection for all attributes.
     *
     * @var bool
     */
    protected $guarded = false;

    /**
     * Set up cascading deletes for subtasks.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::deleting(function ($task) {
            // Cascade delete to subtasks
            $task->subtasks->each(function ($subtask) {
                $subtask->delete();
            });
        });
    }

    /**
     * Mutator to set the 'status' attribute with TaskStatusEnum instance or string value.
     *
     * @param mixed $property
     */
    public function setStatusAttribute(mixed $property): void
    {
        if ($property instanceof TaskStatusEnum) {
            $this->attributes['status'] = $property->value;
        } else {
            $this->attributes['status'] = $property;
        }
    }

    /**
     * Define a one-to-many relationship with subtasks.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subtasks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Task::class, 'parent_id');
    }

    /**
     * Define a many-to-one relationship with parent task.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parentTask(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Task::class, 'parent_id');
    }
}
