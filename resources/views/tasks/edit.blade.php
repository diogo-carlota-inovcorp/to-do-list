@extends('components.layout')

@section('title', 'Editar Tarefa')

@section('content')
<div class="w-full max-w-md bg-white/90 backdrop-blur-lg rounded-2xl shadow-xl p-8">
    <h2 class="text-2xl font-bold mb-6">Editar Tarefa</h2>

    <form method="POST" action="{{ route('tasks.update', $task) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="text-sm font-medium">Título *</label>
            <input type="text"
                   name="title"
                   value="{{ old('title', $task->title) }}"
                   class="w-full mt-1 px-4 py-2 border rounded-lg @error('title') border-red-500 @enderror"
                   required>
        </div>

        <div>
            <label class="text-sm font-medium">Descrição</label>
            <textarea name="description"
                      rows="4"
                      class="w-full mt-1 px-4 py-2 border rounded-lg">{{ old('description', $task->description) }}</textarea>
        </div>

        <!-- STATUS -->
        <div>
            <label class="text-sm font-medium mb-2 block">Status</label>
            <div class="space-y-2">
                <label class="flex items-center gap-2 cursor-pointer p-2 rounded hover:bg-gray-50">
                    <input type="radio" name="status" value="pendente" {{ old('status', $task->status) == 'pendente' ? 'checked' : '' }} class="w-4 h-4">
                    <span class="text-sm">
                        <span class="inline-block w-3 h-3 rounded-full bg-gray-500 mr-2"></span>
                        ⭕ Pendente - Ainda não começou
                    </span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer p-2 rounded hover:bg-gray-50">
                    <input type="radio" name="status" value="iniciado" {{ old('status', $task->status) == 'iniciado' ? 'checked' : '' }} class="w-4 h-4">
                    <span class="text-sm">
                        <span class="inline-block w-3 h-3 rounded-full bg-blue-500 mr-2"></span>
                        🔵 Iniciado - Primeiros passos dados
                    </span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer p-2 rounded hover:bg-gray-50">
                    <input type="radio" name="status" value="em_andamento" {{ old('status', $task->status) == 'em_andamento' ? 'checked' : '' }} class="w-4 h-4">
                    <span class="text-sm">
                        <span class="inline-block w-3 h-3 rounded-full bg-purple-500 mr-2"></span>
                        🟣 Em Andamento - Em progresso ativo
                    </span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer p-2 rounded hover:bg-gray-50">
                    <input type="radio" name="status" value="concluido" {{ old('status', $task->status) == 'concluido' ? 'checked' : '' }} class="w-4 h-4">
                    <span class="text-sm">
                        <span class="inline-block w-3 h-3 rounded-full bg-green-500 mr-2"></span>
                        ✅ Concluído - Completado com sucesso
                    </span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer p-2 rounded hover:bg-gray-50">
                    <input type="radio" name="status" value="expirado" {{ old('status', $task->status) == 'expirado' ? 'checked' : '' }} class="w-4 h-4" {{ $task->due_date && \Carbon\Carbon::parse($task->due_date)->isPast() ? '' : 'disabled' }}>
                    <span class="text-sm {{ $task->due_date && \Carbon\Carbon::parse($task->due_date)->isPast() ? '' : 'text-gray-400' }}">
                        <span class="inline-block w-3 h-3 rounded-full bg-red-500 mr-2"></span>
                        ❌ Expirado - Prazo ultrapassado
                    </span>
                </label>
            </div>
            @if($task->due_date && \Carbon\Carbon::parse($task->due_date)->isPast() && $task->status != 'concluido')
                <p class="text-xs text-red-500 mt-1">⚠️ Esta tarefa está com prazo expirado!</p>
            @endif
        </div>

        <!-- PRIORIDADE -->
        <div>
            <label class="text-sm font-medium mb-2 block">Prioridade</label>
            <div class="flex gap-3">
                <label class="flex items-center gap-2">
                    <input type="radio" name="priority" value="baixa" {{ old('priority', $task->priority) == 'baixa' ? 'checked' : '' }}>
                    <span>🟢 Baixa</span>
                </label>
                <label class="flex items-center gap-2">
                    <input type="radio" name="priority" value="media" {{ old('priority', $task->priority) == 'media' ? 'checked' : '' }}>
                    <span>🟠 Média</span>
                </label>
                <label class="flex items-center gap-2">
                    <input type="radio" name="priority" value="alta" {{ old('priority', $task->priority) == 'alta' ? 'checked' : '' }}>
                    <span>🔴 Alta</span>
                </label>
            </div>
        </div>

        <!-- DATA -->
        <div>
            <label class="text-sm font-medium">Data de Vencimento</label>
            <input type="date"
                   name="due_date"
                   value="{{ old('due_date', $task->due_date ? $task->due_date->format('Y-m-d') : '') }}"
                   class="w-full mt-1 px-4 py-2 border rounded-lg">
        </div>

        <!-- CATEGORIA -->
        <div>
            <label class="text-sm font-medium mb-2 block">Categoria</label>
            <div class="flex gap-2">
                <input type="text"
                       name="category"
                       value="{{ old('category', $task->category) }}"
                       placeholder="Ex: Trabalho, Estudo..."
                       class="flex-1 px-4 py-2 border rounded-lg">
                <select name="category_color" class="px-3 py-2 border rounded-lg">
                    <option value="#3B82F6" {{ $task->category_color == '#3B82F6' ? 'selected' : '' }}>🔵 Azul</option>
                    <option value="#10B981" {{ $task->category_color == '#10B981' ? 'selected' : '' }}>🟢 Verde</option>
                    <option value="#F59E0B" {{ $task->category_color == '#F59E0B' ? 'selected' : '' }}>🟡 Amarelo</option>
                    <option value="#EF4444" {{ $task->category_color == '#EF4444' ? 'selected' : '' }}>🔴 Vermelho</option>
                    <option value="#8B5CF6" {{ $task->category_color == '#8B5CF6' ? 'selected' : '' }}>🟣 Roxo</option>
                    <option value="#EC4899" {{ $task->category_color == '#EC4899' ? 'selected' : '' }}>💕 Rosa</option>
                    <option value="#6B7280" {{ $task->category_color == '#6B7280' ? 'selected' : '' }}>⚫ Cinza</option>
                </select>
            </div>
        </div>

        <button type="submit" class="w-full bg-black text-white py-2 rounded-lg hover:opacity-90">
            Atualizar Tarefa
        </button>

        <div class="flex gap-2 mt-2">
            <a href="{{ route('tasks.show', $task) }}" class="flex-1 text-center bg-gray-200 text-gray-700 py-2 rounded-lg hover:bg-gray-300">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
