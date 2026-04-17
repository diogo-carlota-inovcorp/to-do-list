<?php

use App\Models\Task;
use App\Models\User;

it('permite eliminar uma tarefa', function () {
    $user = User::factory()->create();

    $task = Task::factory()->create([
        'user_id' => $user->id,
    ]);

    $this->actingAs($user)
        ->delete("/tasks/{$task->id}")
        ->assertRedirect();

    $this->assertDatabaseMissing('tasks', [
        'id' => $task->id,
    ]);
});
