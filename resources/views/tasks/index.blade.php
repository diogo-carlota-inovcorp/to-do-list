@extends('components.layout')

@section('title', 'Minhas Tarefas')

@section('content')

@php
    $baseParams = request()->except([]);
@endphp

<div class="w-full max-w-7xl mx-auto grid lg:grid-cols-3 gap-6">

    <!-- CALENDAR PANEL -->
    <div class="lg:col-span-1">
        <div class="bg-white/10 backdrop-blur-xl border border-white/10 rounded-3xl p-6 shadow-xl sticky top-6">

            <!-- HEADER -->
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-white/90">
                    {{ $calendarData['monthName'] }} {{ $year }}
                </h3>

                <div class="flex gap-2">
                    @php
                        $params = request()->all();
                        unset($params['month'], $params['year']);

                        $prevMonth = $month == 1 ? 12 : $month - 1;
                        $prevYear = $month == 1 ? $year - 1 : $year;

                        $nextMonth = $month == 12 ? 1 : $month + 1;
                        $nextYear = $month == 12 ? $year + 1 : $year;
                    @endphp

                    <a href="{{ route('tasks.index', array_merge($params, ['month' => $prevMonth, 'year' => $prevYear])) }}"
                       class="px-2 py-1 rounded-lg bg-white/10 hover:bg-white/20 transition">
                        <
                    </a>

                    <a href="{{ route('tasks.index', array_merge($baseParams, ['month' => $nextMonth, 'year' => $nextYear])) }}"
                    class="px-2 py-1 rounded-lg bg-white/10 hover:bg-white/20 transition">
                        >
                    </a>
                </div>
            </div>

            <!-- WEEK DAYS -->
            <div class="grid grid-cols-7 text-center text-xs text-white/40 mb-2">
                <div>D</div><div>S</div><div>T</div><div>Q</div><div>Q</div><div>S</div><div>S</div>
            </div>

            <!-- DAYS -->
            <div class="space-y-1">
                @foreach($calendarData['weeks'] as $week)
                    <div class="grid grid-cols-7 gap-1">
                        @foreach($week as $day)
                            @if($day)
                               <a href="{{ route('tasks.index', array_merge($baseParams, ['selected_date' => $day['date']])) }}"
                                    class="text-center p-2 rounded-lg text-sm transition
                                    {{ $day['isToday'] ? 'bg-indigo-500 text-white shadow-md' :
                                        (request('selected_date') == $day['date'] ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10') }}">
                                        {{ $day['day'] }}

                                        @if($day['tasks']->count() > 0)
                                            <div class="flex justify-center gap-0.5 mt-1">
                                                @foreach($day['tasks']->take(3) as $task)
                                                    <div class="w-1.5 h-1.5 rounded-full opacity-80"
                                                        style="background-color: {{ $task->category_color ?? '#fff' }}"></div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </a>
                            @else
                                <div></div>
                            @endif
                        @endforeach
                    </div>
                @endforeach
            </div>

            <!-- UPCOMING -->
            <div class="mt-6">
                <h4 class="text-sm font-semibold mb-3 text-white/80">Tarefas deste mês</h4>

                <div class="space-y-2 max-h-64 overflow-y-auto">
                    @forelse($allTasksForDots as $task)
                        <a href="{{ route('tasks.show', $task) }}"
                           class="block p-3 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 transition">

                            <div class="flex justify-between">
                                <div>
                                    <p class="text-sm font-medium">{{ $task->title }}</p>
                                    <p class="text-xs text-white/50">
                                        {{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y') }}
                                    </p>
                                </div>

                                @if($task->category)
                                    <div class="w-2 h-2 rounded-full mt-1"
                                         style="background-color: {{ $task->category_color }}"></div>
                                @endif
                            </div>

                        </a>
                    @empty
                        <p class="text-xs text-white/40 text-center py-4">
                            Sem tarefas este mês
                        </p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>


    <!-- TASKS PANEL -->
    <div class="lg:col-span-2">
        <div class="bg-white/10 backdrop-blur-xl border border-white/10 rounded-3xl p-8 shadow-xl">

            <!-- HEADER -->
            <div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-semibold">Minhas Tarefas</h2>

    <div class="flex gap-2">


        <!-- NOVA TAREFA -->
        <a href="{{ route('tasks.create') }}"
           class="px-5 py-2 bg-indigo-500 hover:bg-indigo-600 rounded-xl shadow-lg transition">
            + Nova
        </a>

    </div>
</div>

<form method="GET" class="mb-4 flex flex-wrap gap-2">

    <!-- PRIORIDADE -->
    <select name="priority" class="bg-white/10 text-white rounded px-2 py-1">
        <option value="">Prioridade</option>
        <option value="baixa" @selected(request('priority')=='baixa')>Baixa</option>
        <option value="media" @selected(request('priority')=='media')>Média</option>
        <option value="alta" @selected(request('priority')=='alta')>Alta</option>
    </select>

    <!-- STATUS -->
    <select name="status" class="bg-white/10 text-white rounded px-2 py-1">
        <option value="">Status</option>
        <option value="pendente" @selected(request('status')=='pendente')>Pendente</option>
        <option value="iniciado" @selected(request('status')=='iniciado')>Iniciado</option>
        <option value="em_andamento" @selected(request('status')=='em_andamento')>Em andamento</option>
        <option value="concluido" @selected(request('status')=='concluido')>Concluído</option>
    </select>

        <!-- DATA DE VENCIMENTO -->
    <input type="date"
           name="due_date"
           value="{{ request('due_date') }}"
           class="bg-white/10 text-white rounded px-3 py-1">

    <button class="bg-indigo-500 px-3 py-1 rounded">
        Filtrar
    </button>

     <a href="{{ route('tasks.index', ['month' => now()->month, 'year' => now()->year]) }}"
           class="px-4 py-2 bg-white/10 hover:bg-white/20 rounded-xl transition text-sm">
            Limpar
        </a>

</form>

            <!-- TASK LIST -->
            <div class="space-y-3">

                @forelse($tasks as $task)

                    <a href="{{ route('tasks.show', $task) }}"
                       class="block p-5 rounded-xl bg-white/5 border border-white/10
                              hover:bg-white/10 hover:scale-[1.01] transition-all">

                        <div class="flex justify-between items-start">

                            <div class="space-y-2">

                                <!-- TAGS -->
                                <div class="flex gap-2 flex-wrap text-xs">

                                    <span class="px-2 py-1 rounded bg-white/10">
                                        {{ $task->status_info['label'] }}
                                    </span>

                                    @if($task->category)
                                        <span class="px-2 py-1 rounded"
                                              style="background-color: {{ $task->category_color }}20; color: {{ $task->category_color }}">
                                            {{ $task->category }}
                                        </span>
                                    @endif

                                    <span class="px-2 py-1 rounded
                                        @if($task->priority == 'alta') bg-red-500/20 text-red-300
                                        @elseif($task->priority == 'media') bg-orange-500/20 text-orange-300
                                        @else bg-green-500/20 text-green-300
                                        @endif">
                                        {{ $task->priority }}
                                    </span>

                                </div>

                                <!-- TITLE -->
                                <h3 class="text-lg font-medium {{ $task->is_completed ? 'line-through text-white/40' : '' }}">
                                    {{ $task->title }}
                                </h3>

                                <!-- DESCRIPTION -->
                                @if($task->description)
                                    <p class="text-sm text-white/60 line-clamp-2">
                                        {{ $task->description }}
                                    </p>
                                @endif

                            </div>

                            <!-- DATE -->
                            @if($task->due_date)
                                <div class="text-xs text-white/50">
                                    {{ \Carbon\Carbon::parse($task->due_date)->format('d/m') }}
                                </div>
                            @endif

                        </div>

                    </a>

                @empty
                    <p class="text-center text-white/50 py-10">
                        Nenhuma tarefa encontrada.
                    </p>
                @endforelse

            </div>

        </div>
    </div>

</div>

@endsection
