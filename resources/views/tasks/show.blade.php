@extends('components.layout')

@section('title', $task->title)

@section('content')

<div class="w-full max-w-4xl mx-auto">

    <!-- BACK -->
    <a href="{{ route('tasks.index') }}"
       class="inline-flex items-center text-white/60 hover:text-white mb-6 transition">
        ← Voltar
    </a>

    <!-- MAIN CARD -->
    <div class="bg-white/10 backdrop-blur-xl border border-white/10 rounded-3xl p-8 shadow-2xl">

        <!-- HEADER -->
        <div class="flex justify-between items-start mb-6">

            <div class="space-y-3">

                <!-- TAGS -->
                <div class="flex gap-2 flex-wrap text-xs">

                    <span class="px-2 py-1 rounded bg-white/10 text-white/70">
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
                <h1 class="text-3xl font-semibold leading-tight
                    {{ $task->status == 'concluido' ? 'line-through text-white/40' : 'text-white' }}">
                    {{ $task->title }}
                </h1>

            </div>

            <!-- EDIT -->
            <a href="{{ route('tasks.edit', $task) }}"
               class="px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 transition text-sm">
                Editar
            </a>

        </div>

        <!-- DESCRIPTION -->
        <div class="mb-8">
            <h3 class="text-sm text-white/60 mb-2">Descrição</h3>
            <p class="text-white/80 leading-relaxed">
                {{ $task->description ?: 'Sem descrição fornecida.' }}
            </p>
        </div>

        <!-- INFO GRID -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">

            <!-- DUE DATE -->
            <div class="p-4 rounded-xl bg-white/5 border border-white/10">
                <p class="text-xs text-white/50">Data de vencimento</p>

                <p class="font-medium mt-1">
                    @if($task->due_date)
                        {{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y') }}

                        @if($task->due_date_status == 'overdue' && $task->status != 'concluido')
                            <span class="text-red-400 text-xs ml-2">Expirada</span>
                        @endif
                    @else
                        —
                    @endif
                </p>
            </div>

            <!-- DAYS LEFT -->
            <div class="p-4 rounded-xl bg-white/5 border border-white/10">
                <p class="text-xs text-white/50">Tempo restante</p>

                <p class="font-medium mt-1">
                    @if($task->due_date)
                        @php $daysLeft = $task->days_left; @endphp

                        @if($daysLeft < 0)
                            <span class="text-red-300">Atrasada {{ abs($daysLeft) }} dias</span>
                        @elseif($daysLeft == 0)
                            <span class="text-orange-300">Vence hoje</span>
                        @else
                            <span class="text-green-300">{{ $daysLeft }} dias</span>
                        @endif
                    @else
                        —
                    @endif
                </p>
            </div>

            <!-- CREATED -->
            <div class="p-4 rounded-xl bg-white/5 border border-white/10">
                <p class="text-xs text-white/50">Criada em</p>
                <p class="font-medium mt-1">{{ $task->created_at->format('d/m/Y') }}</p>
            </div>

            <!-- UPDATED -->
            <div class="p-4 rounded-xl bg-white/5 border border-white/10">
                <p class="text-xs text-white/50">Atualizada</p>
                <p class="font-medium mt-1">{{ $task->updated_at->format('d/m/Y') }}</p>
            </div>

        </div>

        <!-- ACTIONS -->
        <div class="space-y-3">

            @if($task->status != 'concluido' && $task->status != 'expirado')
                <form method="POST" action="{{ route('tasks.complete', $task) }}">
                    @csrf
                    @method('PATCH')

                    <button type="submit"
                            class="w-full py-3 rounded-xl bg-green-500 hover:bg-green-600 transition font-medium">
                        Marcar como concluída
                    </button>
                </form>
            @endif

            <form method="POST"
                  action="{{ route('tasks.destroy', $task) }}"
                  onsubmit="return confirm('Tem certeza que deseja excluir?')">

                @csrf
                @method('DELETE')

                <button type="submit"
                        class="w-full py-3 rounded-xl bg-red-500/20 hover:bg-red-500/30 text-red-300 transition border border-red-500/30">
                    Excluir tarefa
                </button>

            </form>

        </div>

    </div>

</div>

@endsection
