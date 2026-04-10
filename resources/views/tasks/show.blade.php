@extends('components.layout')

@section('title', $task->title)

@section('content')
<div class="w-full max-w-2xl bg-white/90 backdrop-blur-lg rounded-2xl shadow-xl p-8">

    <a href="{{ route('tasks.index') }}" class="inline-flex items-center text-gray-600 hover:text-black mb-6">
        ← Voltar para tarefas
    </a>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Cabeçalho da tarefa -->
    <div class="border-b pb-4 mb-4">
        <div class="flex justify-between items-start">
            <div>
                <div class="flex items-center gap-2 mb-2 flex-wrap">
                    <!-- Status -->
                    <span class="text-xs font-medium px-2 py-1 rounded {{ $task->status_info['bg'] }} {{ $task->status_info['text'] }}">
                        {{ $task->status_info['icon'] }} {{ $task->status_info['label'] }}
                    </span>

                    @if($task->category)
                        <span class="inline-block w-2 h-2 rounded-full" style="background-color: {{ $task->category_color }}"></span>
                        <span class="text-xs px-2 py-1 rounded" style="background-color: {{ $task->category_color }}20; color: {{ $task->category_color }}">
                            {{ $task->category }}
                        </span>
                    @endif

                    <span class="text-xs font-medium uppercase px-2 py-1 rounded
                        @if($task->priority == 'alta') bg-red-100 text-red-700
                        @elseif($task->priority == 'media') bg-orange-100 text-orange-700
                        @else bg-green-100 text-green-700
                        @endif">
                        {{ $task->priority }}
                    </span>
                </div>
                <h1 class="text-3xl font-bold {{ $task->status == 'concluido' ? 'line-through text-gray-500' : '' }}">
                    {{ $task->title }}
                </h1>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('tasks.edit', $task) }}" class="text-blue-600 hover:text-blue-800">✏️ Editar</a>
            </div>
        </div>
    </div>

    <!-- Descrição -->
    <div class="mb-6">
        <h3 class="text-lg font-semibold mb-2">Descrição</h3>
        <p class="text-gray-700 leading-relaxed">
            {{ $task->description ?: 'Sem descrição fornecida.' }}
        </p>
    </div>

    <!-- Informações da tarefa -->
    <div class="grid grid-cols-2 gap-4 mb-6 p-4 bg-gray-50 rounded-lg">
        <div>
            <span class="text-sm text-gray-500">Data de vencimento</span>
            <p class="font-semibold">
                @if($task->due_date)
                    {{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y') }}
                    @if($task->due_date_status == 'overdue' && $task->status != 'concluido')
                        <span class="text-red-500 text-xs ml-2">(Expirada!)</span>
                    @endif
                @else
                    Sem data definida
                @endif
            </p>
        </div>
        <div>
            <span class="text-sm text-gray-500">Dias restantes</span>
            <p class="font-semibold">
                @if($task->due_date)
                    @php
                        $daysLeft = $task->days_left;
                    @endphp
                    @if($daysLeft < 0)
                        <span class="text-red-600">Atrasada há {{ abs($daysLeft) }} dias</span>
                    @elseif($daysLeft == 0)
                        <span class="text-orange-600">Vence hoje!</span>
                    @else
                        <span class="text-green-600">{{ $daysLeft }} dias</span>
                    @endif
                @else
                    —
                @endif
            </p>
        </div>
        <div>
            <span class="text-sm text-gray-500">Criada em</span>
            <p class="font-semibold">{{ $task->created_at->format('d/m/Y') }}</p>
        </div>
        <div>
            <span class="text-sm text-gray-500">Última atualização</span>
            <p class="font-semibold">{{ $task->updated_at->format('d/m/Y') }}</p>
        </div>
    </div>

    <!-- Botão de concluir (se não estiver concluído ou expirado) -->
    @if($task->status != 'concluido' && $task->status != 'expirado')
        <form method="POST" action="{{ route('tasks.complete', $task) }}" class="mb-4">
            @csrf
            @method('PATCH')
            <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition-colors">
                ✅ Marcar como Concluída
            </button>
        </form>
    @endif

    <!-- Botão excluir -->
    <form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('Tem certeza?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700">
            🗑️ Excluir Tarefa
        </button>
    </form>
</div>
@endsection
