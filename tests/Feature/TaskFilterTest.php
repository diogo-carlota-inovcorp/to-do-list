<?php
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Notification;

it('permite filtrar tarefas concluídas', function () {
    $user = User::factory()->create();
    Notification::fake();

    Task::factory()->create([
        'user_id' => $user->id,
        'status' => 'concluido',
    ]);

    Task::factory()->create([
        'user_id' => $user->id,
        'status' => 'pendente',
    ]);

    $this->actingAs($user)
    ->get('/tasks?filter=concluidas')
    ->assertStatus(200);
});
