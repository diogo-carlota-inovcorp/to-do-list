<?php

use App\Models\User;

it('exige um título para criar uma tarefa', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post('/tasks', [
            'title' => '',
        ])
        ->assertSessionHasErrors(['title']);
});
