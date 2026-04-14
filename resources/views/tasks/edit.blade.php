@extends('components.layout')

@section('title', 'Editar Tarefa')

@section('content')
<div class="w-full max-w-2xl bg-white/90 backdrop-blur-lg rounded-2xl shadow-xl p-6">
    <h2 class="text-2xl font-bold mb-4">Editar Tarefa</h2>

    <form method="POST" action="{{ route('tasks.update', $task) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <!-- Linha 1: Título -->
        <div>
            <label class="text-sm font-medium block mb-1">Título *</label>
            <input type="text"
                   name="title"
                   value="{{ old('title', $task->title) }}"
                   class="w-full px-4 py-2 border rounded-lg @error('title') border-red-500 @enderror"
                   required>
        </div>

        <!-- Linha 2: Descrição -->
        <div>
            <label class="text-sm font-medium block mb-1">Descrição</label>
            <textarea name="description"
                      rows="3"
                      class="w-full px-4 py-2 border rounded-lg">{{ old('description', $task->description) }}</textarea>
        </div>

        <!-- Linha 3: Prioridade e Status -->
        <div class="grid grid-cols-2 gap-4">
            <!-- Prioridade -->
            <div>
                <label class="text-sm font-medium block mb-2">Prioridade</label>
                <div class="flex gap-3">
                    <label class="flex items-center gap-1">
                        <input type="radio" name="priority" value="baixa" {{ old('priority', $task->priority) == 'baixa' ? 'checked' : '' }}>
                        <span class="text-sm">Baixa</span>
                    </label>
                    <label class="flex items-center gap-1">
                        <input type="radio" name="priority" value="media" {{ old('priority', $task->priority) == 'media' ? 'checked' : '' }}>
                        <span class="text-sm">Média</span>
                    </label>
                    <label class="flex items-center gap-1">
                        <input type="radio" name="priority" value="alta" {{ old('priority', $task->priority) == 'alta' ? 'checked' : '' }}>
                        <span class="text-sm">Alta</span>
                    </label>
                </div>
            </div>

            <!-- Status -->
            <div>
                <label class="text-sm font-medium block mb-2">Status</label>
                <select name="status" class="w-full px-3 py-2 border rounded-lg">
                    <option value="pendente" {{ old('status', $task->status) == 'pendente' ? 'selected' : '' }}>Pendente</option>
                    <option value="iniciado" {{ old('status', $task->status) == 'iniciado' ? 'selected' : '' }}>Iniciado</option>
                    <option value="em_andamento" {{ old('status', $task->status) == 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                    <option value="concluido" {{ old('status', $task->status) == 'concluido' ? 'selected' : '' }}>Concluído</option>

                    @if($task->due_date && \Carbon\Carbon::parse($task->due_date)->isPast())
                        <option value="expirado" {{ old('status', $task->status) == 'expirado' ? 'selected' : '' }}>Expirado</option>
                    @endif
                </select>
            </div>
        </div>

        <!-- Linha 4: Data e Categoria -->
        <div class="grid grid-cols-2 gap-4">
            <!-- Data -->
            <div>
                <label class="text-sm font-medium block mb-1">Data de Vencimento</label>
                <input type="date"
                       name="due_date"
                       value="{{ old('due_date', $task->due_date ? $task->due_date->format('Y-m-d') : '') }}"
                       class="w-full px-4 py-2 border rounded-lg">
            </div>

            <!-- Categoria -->
            <div>
                <label class="text-sm font-medium block mb-1">Categoria</label>
                <div class="flex gap-2">
                    <input type="text"
                           name="category"
                           value="{{ old('category', $task->category) }}"
                           class="flex-1 px-3 py-2 border rounded-lg">

                    <select name="category_color" class="w-20 px-2 py-2 border rounded-lg">
                        <option value="#3B82F6" {{ $task->category_color == '#3B82F6' ? 'selected' : '' }}>🔵</option>
                        <option value="#10B981" {{ $task->category_color == '#10B981' ? 'selected' : '' }}>🟢</option>
                        <option value="#F59E0B" {{ $task->category_color == '#F59E0B' ? 'selected' : '' }}>🟡</option>
                        <option value="#EF4444" {{ $task->category_color == '#EF4444' ? 'selected' : '' }}>🔴</option>
                        <option value="#8B5CF6" {{ $task->category_color == '#8B5CF6' ? 'selected' : '' }}>🟣</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Aviso -->
        @if($task->due_date && \Carbon\Carbon::parse($task->due_date)->isPast() && $task->status != 'concluido')
            <p class="text-xs text-red-500"> Esta tarefa está com prazo expirado!</p>
        @endif

        <!-- Botões -->
        <div class="flex gap-3 pt-2">
            <button type="submit" class="flex-1 bg-black text-white py-2 rounded-lg hover:opacity-90">
                Atualizar Tarefa
            </button>

            <a href="{{ route('tasks.show', $task) }}"
               class="flex-1 text-center bg-gray-200 text-gray-700 py-2 rounded-lg hover:bg-gray-300">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
