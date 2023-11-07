<?php

namespace App\Models;

use App\Enums\TaskStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static find(mixed $taskId)
 * @method static findOrFail(mixed $taskId)
 * @property mixed $user_id
 */
class Task extends Model
{
    use HasFactory;

    protected $guarded = false;

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'create_at',
        'updated_at',
        'completed_at'
    ];

    // Set up cascading deletes for subtasks
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
    public function setStatusAttribute($property): void
    {
        if ($property instanceof TaskStatusEnum) {
            $this->attributes['status'] = $property->value;
        } else {
            $this->attributes['status'] = $property;
        }
    }
    public function subtasks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Task::class, 'parent_id');
    }

    public function parentTask(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Task::class, 'parent_id');
    }

}
