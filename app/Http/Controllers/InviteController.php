<?php

namespace App\Http\Controllers;
use App\Models\Task;
use App\Models\Notification;

use Illuminate\Http\Request;

public function accept(Request $request)
    {
        $taskId = $request->task_id;

        // attach user to task (example)
        $task = Task::findOrFail($taskId);
        $task->users()->attach(auth()->id());

        // delete notification
        Notification::where('id', $request->notification_id)->delete();

        return back();
    }

public function reject(Request $request)
{
    Notification::where('id', $request->notification_id)->delete();

    return back();
}

public function clear()
{
    Notification::where('user_id', auth()->id())->delete();

    return back();
}
