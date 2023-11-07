<?php

namespace App\Models;

use App\Enums\TaskStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $guarded = false;

    protected $table = 'tasks';

    protected $fillable = [
      'status',
    ];


    public function getStatusAttribute(string $value): TaskStatusEnum
    {
        return TaskStatusEnum::from($value);
    }

    public function setStatusAttribute(TaskStatusEnum $status): void
    {
        $this->attributes['status'] = $status->value;
    }

}
