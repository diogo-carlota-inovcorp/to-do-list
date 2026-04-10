@extends('components.layout')

@section('title', 'Minhas Tarefas')

@section('content')
<div class="w-full max-w-7xl mx-auto">
    <div class="flex gap-6">

        <!-- COLUNA DA ESQUERDA - CALENDÁRIO -->
        <div class="w-1/3">
            <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-xl p-6 sticky top-4">

    <!-- CABEÇALHO DO CALENDÁRIO COM NAVEGAÇÃO -->
<div class="flex justify-between items-center mb-4">
    <h3 class="text-xl font-bold">{{ $calendarData['monthName'] }} {{ $year }}</h3>
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
           class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
            <
        </a>

        <a href="{{ route('tasks.index', array_merge($params, ['month' => $nextMonth, 'year' => $nextYear])) }}"
           class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
            >
        </a>
    </div>
</div>

                <!-- DIAS DA SEMANA -->
                <div class="grid grid-cols-7 gap-1 text-center text-sm mb-2">
                    <div class="font-medium text-gray-500">D</div>
                    <div class="font-medium text-gray-500">S</div>
                    <div class="font-medium text-gray-500">T</div>
                    <div class="font-medium text-gray-500">Q</div>
                    <div class="font-medium text-gray-500">Q</div>
                    <div class="font-medium text-gray-500">S</div>
                    <div class="font-medium text-gray-500">S</div>
                </div>

                <!-- DIAS DO MÊS -->
                <div class="space-y-1">
                    @foreach($calendarData['weeks'] as $week)
                        <div class="grid grid-cols-7 gap-1">
                            @foreach($week as $day)
                                @if($day)
                                    <div class="relative">
                                        <div class="text-center p-2 rounded-lg
                                            {{ $day['isToday'] ? 'bg-black text-white' : 'hover:bg-gray-100' }}
                                            {{ $day['tasks']->count() > 0 && !$day['isToday'] ? 'bg-gray-50' : '' }}">
                                            {{ $day['day'] }}
                                        </div>
                                        @if($day['tasks']->count() > 0)
                                            <div class="flex justify-center gap-0.5 mt-0.5">
                                                @foreach($day['tasks']->take(3) as $task)
                                                    <div class="w-1.5 h-1.5 rounded-full"
                                                         style="background-color: {{ $task->category_color ?? '#000' }}"
                                                         title="{{ $task->title }}"></div>
                                                @endforeach
                                                @if($day['tasks']->count() > 3)
                                                    <div class="w-1.5 h-1.5 rounded-full bg-gray-400" title="+{{ $day['tasks']->count() - 3 }} mais"></div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="p-2"></div>
                                @endif
                            @endforeach
                        </div>
                    @endforeach
                </div>


                <!-- PRÓXIMAS TAREFAS -->
                <div class="mt-6">
                    <h4 class="font-semibold mb-2"> Tarefas deste mês</h4>
                    <div class="space-y-2 max-h-96 overflow-y-auto">
                        @php
                            $currentMonthTasks = $allTasksForDots->filter(function($task) use ($year, $month) {
                                return !$task->is_completed &&
                                       $task->due_date &&
                                       Carbon\Carbon::parse($task->due_date)->year == $year &&
                                       Carbon\Carbon::parse($task->due_date)->month == $month;
                            })->sortBy('due_date');
                        @endphp
                        @forelse($currentMonthTasks as $upcomingTask)
                            <a href="{{ route('tasks.show', $upcomingTask) }}" class="block p-2 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <div class="text-sm font-medium">{{ $upcomingTask->title }}</div>
                                        <div class="text-xs text-gray-500">
                                            {{ Carbon\Carbon::parse($upcomingTask->due_date)->format('d/m/Y') }}
                                        </div>
                                    </div>
                                    @if($upcomingTask->category)
                                        <div class="w-2 h-2 rounded-full" style="background-color: {{ $upcomingTask->category_color }}"></div>
                                    @endif
                                </div>
                            </a>
                        @empty
                            <p class="text-sm text-gray-500 text-center py-4">
                                Nenhuma tarefa programada para este mês
                            </p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- COLUNA DA DIREITA - TAREFAS -->
        <div class="w-2/3">
            <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-xl p-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Minhas Tarefas</h2>
                    <a href="{{ route('tasks.create') }}" class="bg-black text-white px-4 py-2 rounded-lg hover:opacity-90">
                        + Nova Tarefa
                    </a>
                </div>

                @if(session('success'))
                    <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- FILTROS -->
                <div class="mb-6 space-y-3">
                    <!-- Filtro por prioridade -->
                    <div>
                        <label class="text-sm font-medium mb-2 block">Filtrar por prioridade:</label>
                        <div class="flex gap-2 flex-wrap">
                            <a href="{{ route('tasks.index', array_merge(request()->except('priority'), ['month' => $month, 'year' => $year])) }}"
                               class="px-3 py-1 rounded-full text-sm {{ !request('priority') ? 'bg-black text-white' : 'bg-gray-200' }}">
                                Todas
                            </a>
                            <a href="{{ route('tasks.index', array_merge(request()->except('priority'), ['priority' => 'alta', 'month' => $month, 'year' => $year])) }}"
                               class="px-3 py-1 rounded-full text-sm {{ request('priority') == 'alta' ? 'bg-red-600 text-white' : 'bg-red-100' }}">
                                Alta
                            </a>
                            <a href="{{ route('tasks.index', array_merge(request()->except('priority'), ['priority' => 'media', 'month' => $month, 'year' => $year])) }}"
                               class="px-3 py-1 rounded-full text-sm {{ request('priority') == 'media' ? 'bg-orange-600 text-white' : 'bg-orange-100' }}">
                             Média
                            </a>
                            <a href="{{ route('tasks.index', array_merge(request()->except('priority'), ['priority' => 'baixa', 'month' => $month, 'year' => $year])) }}"
                               class="px-3 py-1 rounded-full text-sm {{ request('priority') == 'baixa' ? 'bg-green-600 text-white' : 'bg-green-100' }}">
                             Baixa
                            </a>
                        </div>
                    </div>

                    <!-- Filtro por categoria -->
                    @if($categories->count() > 0)
                    <div>
                        <label class="text-sm font-medium mb-2 block">Filtrar por categoria:</label>
                        <div class="flex gap-2 flex-wrap">
                            <a href="{{ route('tasks.index', array_merge(request()->except('category'), ['month' => $month, 'year' => $year])) }}"
                               class="px-3 py-1 rounded-full text-sm {{ !request('category') ? 'bg-black text-white' : 'bg-gray-200' }}">
                                Todas
                            </a>
                            @foreach($categories as $cat)
                                <a href="{{ route('tasks.index', array_merge(request()->except('category'), ['category' => $cat->category, 'month' => $month, 'year' => $year])) }}"
                                   class="px-3 py-1 rounded-full text-sm {{ request('category') == $cat->category ? 'text-white' : '' }}"
                                   style="{{ request('category') == $cat->category ? 'background-color: ' . $cat->category_color : 'background-color: ' . $cat->category_color . '20; color: ' . $cat->category_color }}">
                                    {{ $cat->category }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Filtro por status -->

                    <div>
                        <label class="text-sm font-medium mb-2 block">Filtrar por status:</label>
                        <div class="flex gap-2 flex-wrap">
                            <a href="{{ route('tasks.index', array_merge(request()->except('status'), ['month' => $month, 'year' => $year])) }}"
                            class="px-3 py-1 rounded-full text-sm {{ !request('status') ? 'bg-black text-white' : 'bg-gray-200' }}">
                                Todos
                            </a>
                            <a href="{{ route('tasks.index', array_merge(request()->except('status'), ['status' => 'pendente', 'month' => $month, 'year' => $year])) }}"
                            class="px-3 py-1 rounded-full text-sm {{ request('status') == 'pendente' ? 'bg-gray-600 text-white' : 'bg-gray-100' }}">
                                 Pendente
                            </a>
                            <a href="{{ route('tasks.index', array_merge(request()->except('status'), ['status' => 'iniciado', 'month' => $month, 'year' => $year])) }}"
                            class="px-3 py-1 rounded-full text-sm {{ request('status') == 'iniciado' ? 'bg-blue-600 text-white' : 'bg-blue-100' }}">
                                 Iniciado
                            </a>
                            <a href="{{ route('tasks.index', array_merge(request()->except('status'), ['status' => 'em_andamento', 'month' => $month, 'year' => $year])) }}"
                            class="px-3 py-1 rounded-full text-sm {{ request('status') == 'em_andamento' ? 'bg-purple-600 text-white' : 'bg-purple-100' }}">
                                 Em Andamento
                            </a>
                            <a href="{{ route('tasks.index', array_merge(request()->except('status'), ['status' => 'concluido', 'month' => $month, 'year' => $year])) }}"
                            class="px-3 py-1 rounded-full text-sm {{ request('status') == 'concluido' ? 'bg-green-600 text-white' : 'bg-green-100' }}">
                                Concluído
                            </a>
                            <a href="{{ route('tasks.index', array_merge(request()->except('status'), ['status' => 'expirado', 'month' => $month, 'year' => $year])) }}"
                            class="px-3 py-1 rounded-full text-sm {{ request('status') == 'expirado' ? 'bg-red-600 text-white' : 'bg-red-100' }}">
                                Expirado
                            </a>
                        </div>
                    </div>

                </div>

                <!-- LISTA DE TAREFAS -->
                @if($tasks->isEmpty())
                    <p class="text-gray-500 text-center py-8">
                        Nenhuma tarefa encontrada.
                    </p>
                @else
                    <div class="space-y-3">
                        @foreach($tasks as $task)
                            <a href="{{ route('tasks.show', $task) }}" class="block border rounded-lg p-4 hover:shadow-md transition-shadow cursor-pointer">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1 flex-wrap">
                                     <span class="text-xs px-2 py-0.5 rounded {{ $task->status_info['bg'] }} {{ $task->status_info['text'] }}">
                                                                {{ $task->status_info['label'] }}
                                    </span>
                                            @if($task->category)
                                                <span class="inline-block w-2 h-2 rounded-full" style="background-color: {{ $task->category_color }}"></span>
                                                <span class="text-xs px-2 py-0.5 rounded" style="background-color: {{ $task->category_color }}20; color: {{ $task->category_color }}">
                                                    {{ $task->category }}
                                                </span>
                                            @endif
                                            <span class="inline-block w-2 h-2 rounded-full
                                                @if($task->priority == 'alta') bg-red-500
                                                @elseif($task->priority == 'media') bg-orange-500
                                                @else bg-green-500
                                                @endif">
                                            </span>
                                            <span class="text-xs font-medium uppercase
                                                @if($task->priority == 'alta') text-red-600
                                                @elseif($task->priority == 'media') text-orange-600
                                                @else text-green-600
                                                @endif">
                                                {{ $task->priority }}
                                            </span>
                                            @if($task->due_date)
                                                <span class="text-xs
                                                    @if($task->days_left < 0) text-red-600
                                                    @elseif($task->days_left <= 3) text-orange-600
                                                    @else text-gray-500
                                                    @endif">
                                                     {{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y') }}
                                                    @if($task->days_left < 0)
                                                        (Atrasada)
                                                    @elseif($task->days_left == 0)
                                                        (Hoje)
                                                    @else
                                                        ({{ $task->days_left }} dias)
                                                    @endif
                                                </span>
                                            @endif
                                        </div>
                                        <h3 class="font-semibold {{ $task->is_completed ? 'line-through text-gray-500' : '' }}">
                                            {{ $task->title }}
                                        </h3>
                                        @if($task->description)
                                            <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ $task->description }}</p>
                                        @endif
                                    </div>
                                    @if($task->is_completed)
                                        <span class="text-green-600 text-sm"> Concluída</span>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection

@push('styles')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush
