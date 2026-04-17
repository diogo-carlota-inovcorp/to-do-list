<?php

use App\Models\Task;
use App\Models\User;

it('permite marcar uma tarefa como concluída', function () {
    $user = User::factory()->create();

    $task = Task::factory()->create([
        'user_id' => $user->id,
        'status' => 'pendente',
    ]);

   $this->actingAs($user)
    ->patch("/tasks/{$task->id}/complete")
    ->assertRedirect();

    expect($task->fresh()->status)->toBe('concluido');
});
