<?php
use App\Models\Task;
use App\Models\User;

it('permite atualizar uma tarefa', function () {
    $user = User::factory()->create();

$task = Task::factory()->create([
    'user_id' => $user->id,
    'title' => 'Original',
]);

$this->actingAs($user)
    ->put("/tasks/{$task->id}", [
        'title' => 'Título atualizado',
        'description' => $task->description,
        'due_date' => $task->due_date,
        'status' => $task->status,
        'priority' => 'media',
    ])
    ->assertRedirect();

expect($task->fresh()->title)->toBe('Título atualizado');
});
