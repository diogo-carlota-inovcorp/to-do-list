<?php

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Notification;

it ('mostra as tarefas do utilizador autenticado', function () {

    Notification::fake();

    $user = \App\Models\User::factory()->create();

    \App\Models\Task::factory()->create([
        'user_id' => $user->id,
        'title' => 'Tarefa 1',
    ]);

    $this->actingAs($user)
        ->get('/tasks')
        ->assertOk()
        ->assertSee('Tarefa 1');
});
