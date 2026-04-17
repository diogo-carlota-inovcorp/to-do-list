<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Notifications\TaskExpiredNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function sendExpiredNotifications()
    {
        $tasks = Task::where('due_date', '<', now())
            ->whereNull('notified_at')
            ->get();

        foreach ($tasks as $task) {
            $user = $task->user;

            $user->notify(new TaskExpiredNotification($task));

            $task->update([
                'notified_at' => now()
            ]);
        }

        return "Notifications sent";
    }

    public function clear()
    {
        $user = auth()->user();

        $user->notifications()->delete();

        return back();
    }
}
