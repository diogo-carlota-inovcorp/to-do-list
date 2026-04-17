@extends('components.layout')

@section('title', 'Editar Tarefa')

@section('content')

<div class="w-full max-w-3xl mx-auto">

    <div class="bg-white/10 backdrop-blur-xl border border-white/10 rounded-3xl p-8 shadow-2xl">

        <!-- HEADER -->
        <div class="mb-8">
            <h2 class="text-3xl font-semibold">Editar Tarefa</h2>
            <p class="text-white/60 text-sm mt-1">
                Atualiza os detalhes da tua tarefa
            </p>
        </div>

        <form method="POST" action="{{ route('tasks.update', $task) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- TITLE -->
            <div>
                <label class="text-sm text-white/70 block mb-2">Título *</label>
                <input type="text"
                       name="title"
                       value="{{ old('title', $task->title) }}"
                       class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/10 text-white">
            </div>

            <!-- DESCRIÇÃO-->
            <div>
                <label class="text-sm text-white/70 block mb-2">Descrição</label>
                <textarea name="description"
                          rows="4"
                          class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/10 text-white">{{ old('description', $task->description) }}</textarea>
            </div>

            <!-- PRIORIDADE + STATUS -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- PRIORIDADE-->
                <div>
                    <label class="text-sm text-white/70 block mb-2">Prioridade</label>

                    <div class="flex gap-2">

                        <label class="flex-1 text-center cursor-pointer">
                            <input type="radio" name="priority" value="baixa" class="hidden peer"
                                   {{ old('priority', $task->priority) == 'baixa' ? 'checked' : '' }}>
                            <div class="py-2 rounded-xl bg-green-500/20 text-green-300 border border-green-500/30
                                        peer-checked:bg-green-500 peer-checked:text-white">
                                Baixa
                            </div>
                        </label>

                        <label class="flex-1 text-center cursor-pointer">
                            <input type="radio" name="priority" value="media" class="hidden peer"
                                   {{ old('priority', $task->priority) == 'media' ? 'checked' : '' }}>
                            <div class="py-2 rounded-xl bg-orange-500/20 text-orange-300 border border-orange-500/30
                                        peer-checked:bg-orange-500 peer-checked:text-white">
                                Média
                            </div>
                        </label>

                        <label class="flex-1 text-center cursor-pointer">
                            <input type="radio" name="priority" value="alta" class="hidden peer"
                                   {{ old('priority', $task->priority) == 'alta' ? 'checked' : '' }}>
                            <div class="py-2 rounded-xl bg-red-500/20 text-red-300 border border-red-500/30
                                        peer-checked:bg-red-500 peer-checked:text-white">
                                Alta
                            </div>
                        </label>

                    </div>
                </div>

                <!-- STATUS -->
                <div>
                    <label class="text-sm text-white/70 block mb-2">Status</label>

                    <select name="status"
                            class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/10 text-white">

                        <option value="pendente" {{ old('status', $task->status) == 'pendente' ? 'selected' : '' }}>Pendente</option>
                        <option value="iniciado" {{ old('status', $task->status) == 'iniciado' ? 'selected' : '' }}>Iniciado</option>
                        <option value="em_andamento" {{ old('status', $task->status) == 'em_andamento' ? 'selected' : '' }}>Em andamento</option>
                        <option value="concluido" {{ old('status', $task->status) == 'concluido' ? 'selected' : '' }}>Concluído</option>

                        @if($task->due_date && \Carbon\Carbon::parse($task->due_date)->isPast())
                            <option value="expirado" {{ old('status', $task->status) == 'expirado' ? 'selected' : '' }}>Expirado</option>
                        @endif

                    </select>
                </div>

            </div>

            <!-- DATA + CATEGORIA -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- DATA -->
                <div>
                    <label class="text-sm text-white/70 block mb-2">Data</label>
                    <input type="date"
                           name="due_date"
                           value="{{ old('due_date', $task->due_date ? $task->due_date->format('Y-m-d') : '') }}"
                           class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/10 text-white">
                </div>

                <!-- CATEGORIA-->
                <div>
                    <label class="text-sm text-white/70 block mb-2">Categoria</label>

                    <input type="text"
                           name="category"
                           value="{{ old('category', $task->category) }}"
                           placeholder="Ex: Trabalho, Estudo..."
                           class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/10 text-white">
                </div>

            </div>

            <!-- CATEGORIA COR -->
            <div>
                <label class="text-sm text-white/70 block mb-2">Cor da categoria</label>

                <input type="color"
                       name="category_color"
                       value="{{ old('category_color', $task->category_color ?? '#3B82F6') }}"
                       class="w-12 h-10 p-0 border border-white/10 rounded-lg cursor-pointer bg-transparent">
            </div>

            <!-- WARNING -->
            @if($task->due_date && \Carbon\Carbon::parse($task->due_date)->isPast() && $task->status != 'concluido')
                <div class="p-3 rounded-xl bg-red-500/10 border border-red-500/20 text-red-300 text-sm">
                    ⚠️ Esta tarefa está com prazo expirado
                </div>
            @endif

            <!-- ACTIONS -->
            <div class="flex gap-4 pt-4">

                <button type="submit"
                        class="flex-1 py-3 bg-indigo-500 hover:bg-indigo-600 rounded-xl font-medium">
                    Atualizar
                </button>

                <a href="{{ route('tasks.show', $task) }}"
                   class="flex-1 text-center py-3 bg-white/10 hover:bg-white/20 rounded-xl">
                    Cancelar
                </a>

            </div>

        </form>
    </div>

</div>

@endsection
