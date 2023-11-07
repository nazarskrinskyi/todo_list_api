<?php

namespace Database\Factories;

use App\Enums\TaskStatusEnum;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => $status = $this->faker->randomElement(TaskStatusEnum::values()),
            'parent_id' => $this->faker->numberBetween(1, 10),
            'priority' => $this->faker->numberBetween(1, 5),
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'user_id' => \App\Models\User::factory(),
            'completed_at' => $status === TaskStatusEnum::DONE ? Carbon::now() : null,
        ];
    }
}
