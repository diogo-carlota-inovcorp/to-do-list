<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');



Schedule::call(function () {
    $tasks = \App\Models\Task::where('due_date', '<', now())
        ->whereNull('notified_at')
        ->get();

    foreach ($tasks as $task) {
        $task->user->notify(new \App\Notifications\TaskExpiredNotification($task));
        $task->update(['notified_at' => now()]);
    }
})->everyMinute();
