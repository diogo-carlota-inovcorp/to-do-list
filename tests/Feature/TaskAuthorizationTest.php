<?php

use App\Models\Task;
use App\Models\User;

it('impede um utilizador de editar tarefas de outro utilizador', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $task = Task::factory()->create([
        'user_id' => $user1->id,
    ]);

    $this->actingAs($user2)
        ->put("/tasks/{$task->id}", [
            'title' => 'hack',
        ])
        ->assertForbidden();
});
