<?php

use App\Models\User;

it('permite que um utilizador crie uma tarefa', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post('/tasks', [
            'title' => 'Nova tarefa',
            'description' => 'Teste',
            'priority' => 'media',
            'status' => 'pendente',
            'due_date' => now()->addDays(2)->toDateString(),
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('tasks', [
        'title' => 'Nova tarefa',
        'priority' => 'media',
        'status' => 'pendente',
    ]);
});
