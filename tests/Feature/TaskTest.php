<?php

use App\Models\Task;

it('verifica se uma tarefa está atrasada', function () {
    $task = new Task([
        'due_date' => now()->subDay(),
        'is_completed' => false,
    ]);

    expect($task->isOverdue())->toBeTrue();
});
