<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'due_date' => $this->faker->date(),
            'status' => 'pendente',
            'priority' => $this->faker->randomElement(['baixa', 'media', 'alta']),
            'is_completed' => false,
            'user_id' => User::factory(),
        ];
    }
}
