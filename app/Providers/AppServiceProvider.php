<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use App\Models\Task;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
{
    Carbon::setLocale('pt');

    View::composer('*', function ($view) {

        if (!auth()->check()) {
            $view->with('notificationsCount', 0);
            $view->with('notifications', []);
            return;
        }

        $userId = auth()->id();

        //  CONTADOR
        $count = 0;

        // tarefas a expirar em 3 dias
        $count += Task::where('user_id', $userId)
            ->whereDate('due_date', now()->addDays(3))
            ->count();

        // tarefas expiradas
        $count += Task::where('user_id', $userId)
            ->whereDate('due_date', '<', now())
            ->where('is_completed', false)
            ->count();

        //  NOTIFICAÇÕES (texto)
        $notifications = [];


        // expirar
        $expiring = Task::where('user_id', $userId)
            ->whereDate('due_date', now()->addDays(3))
            ->get();

        foreach ($expiring as $task) {
            $notifications[] = "A tarefa '{$task->title}' expira em 3 dias";
        }

        // expiradas
        $expired = Task::where('user_id', $userId)
            ->whereDate('due_date', '<', now())
            ->where('is_completed', false)
            ->get();

        foreach ($expired as $task) {
            $notifications[] = "A tarefa '{$task->title}' expirou";
        }

        // ENVIAR PARA TODAS AS VIEWS
        $view->with('notificationsCount', $count);
        $view->with('notifications', $notifications);
    });
}
}
