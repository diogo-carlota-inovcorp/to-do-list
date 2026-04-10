<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TaskController extends Controller
{

    public function index(Request $request)
    {
        // Atualizar status das tarefas expiradas
        $expiredTasks = Task::where('user_id', auth()->id())
                           ->where('status', '!=', 'concluido')
                           ->where('due_date', '<', Carbon::today())
                           ->get();

        foreach ($expiredTasks as $task) {
            $task->update(['status' => 'expirado']);
        }

        $query = Task::where('user_id', auth()->id());

        // Filtrar por prioridade
        if ($request->has('priority') && in_array($request->priority, ['baixa', 'media', 'alta'])) {
            $query->where('priority', $request->priority);
        }

        // Filtrar por categoria
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        // Filtrar por status
        if ($request->has('status') && in_array($request->status, ['pendente', 'iniciado', 'em_andamento', 'concluido', 'expirado'])) {
            $query->where('status', $request->status);
        }

        $tasks = $query->orderByRaw("FIELD(status, 'pendente', 'iniciado', 'em_andamento', 'concluido', 'expirado')")
                       ->orderByRaw("FIELD(priority, 'alta', 'media', 'baixa')")
                       ->get();

        $currentPriority = $request->get('priority');
        $currentCategory = $request->get('category');
        $currentStatus = $request->get('status');

        // Pegar dados do calendário
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $allTasks = Task::where('user_id', auth()->id())
                       ->whereNotNull('due_date')
                       ->whereYear('due_date', $year)
                       ->whereMonth('due_date', $month)
                       ->get();

        $allTasksForDots = Task::where('user_id', auth()->id())
                               ->whereNotNull('due_date')
                               ->get();

        $categories = Task::where('user_id', auth()->id())
                         ->whereNotNull('category')
                         ->select('category', 'category_color')
                         ->distinct()
                         ->get();

        $calendarData = $this->getCalendarData($year, $month, $allTasks);

        return view('tasks.index', compact(
            'tasks',
            'currentPriority',
            'currentCategory',
            'currentStatus',
            'allTasksForDots',
            'categories',
            'calendarData',
            'month',
            'year'
        ));
    }

    private function getCalendarData($year, $month, $tasks)
    {
        $firstDayOfMonth = Carbon::createFromDate($year, $month, 1);
        $daysInMonth = $firstDayOfMonth->daysInMonth;
        $startOffset = $firstDayOfMonth->dayOfWeek;

        $weeks = [];
        $currentDay = 1;

        while ($currentDay <= $daysInMonth) {
            $week = [];
            for ($i = 0; $i < 7; $i++) {
                if ($currentDay > $daysInMonth) {
                    $week[] = null;
                } elseif ($currentDay == 1 && $i < $startOffset) {
                    $week[] = null;
                } else {
                    $currentDate = Carbon::createFromDate($year, $month, $currentDay);
                    $tasksOnDay = $tasks->filter(function($task) use ($currentDate) {
                        return $task->due_date && Carbon::parse($task->due_date)->format('Y-m-d') == $currentDate->format('Y-m-d');
                    });

                    $week[] = [
                        'day' => $currentDay,
                        'date' => $currentDate,
                        'tasks' => $tasksOnDay,
                        'isToday' => $currentDate->isToday(),
                    ];
                    $currentDay++;
                }
            }
            $weeks[] = $week;
        }

        return [
            'weeks' => $weeks,
            'monthName' => $firstDayOfMonth->translatedFormat('F'),
            'year' => $year,
            'month' => $month,
        ];
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:baixa,media,alta',
            'due_date' => 'nullable|date',
            'category' => 'nullable|string|max:50',
            'category_color' => 'nullable|string|max:20',
            'status' => 'required|in:pendente,iniciado,em_andamento,concluido,expirado',
        ]);

        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
            'category' => $request->category,
            'category_color' => $request->category_color,
            'user_id' => auth()->id(),
            'is_completed' => $request->status == 'concluido',
            'status' => $request->status,
        ]);

        return redirect()->route('tasks.index')->with('success', 'Tarefa criada com sucesso!');
    }

    public function show(Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            abort(403);
        }

        // Atualizar status se necessário
        $task->updateStatusBasedOnDate();

        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            abort(403);
        }

        // Atualizar status se necessário
        $task->updateStatusBasedOnDate();

        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:baixa,media,alta',
            'due_date' => 'nullable|date',
            'category' => 'nullable|string|max:50',
            'category_color' => 'nullable|string|max:20',
            'status' => 'required|in:pendente,iniciado,em_andamento,concluido,expirado',
        ]);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
            'category' => $request->category,
            'category_color' => $request->category_color,
            'status' => $request->status,
            'is_completed' => $request->status == 'concluido',
        ]);

        return redirect()->route('tasks.show', $task)->with('success', 'Tarefa atualizada!');
    }

    public function destroy(Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            abort(403);
        }

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Tarefa excluída!');
    }

    public function complete(Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            abort(403);
        }

        $task->update([
            'is_completed' => true,
            'status' => 'concluido'
        ]);

        return redirect()->route('tasks.show', $task)->with('success', 'Tarefa concluída! Parabéns!');
    }

    // Atualizar status via AJAX (opcional)
    public function updateStatus(Request $request, Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pendente,iniciado,em_andamento,concluido,expirado'
        ]);

        $task->update([
            'status' => $request->status,
            'is_completed' => $request->status == 'concluido'
        ]);

        return response()->json(['success' => true, 'status' => $task->status_info]);
    }
}
