@extends('components.layout')

@section('title', 'Nova Tarefa')

@section('content')

<div class="w-full max-w-3xl mx-auto">

    <div class="bg-white/10 backdrop-blur-xl border border-white/10 rounded-3xl p-8 shadow-2xl">

        <!-- HEADER -->
        <div class="mb-8">
            <h2 class="text-3xl font-semibold">Nova Tarefa</h2>
            <p class="text-white/60 text-sm mt-1">
                Cria uma nova tarefa e mantém tudo organizado
            </p>
        </div>

        <form method="POST" action="{{ route('tasks.store') }}" class="space-y-6">
            @csrf

            <!-- TÍTULO -->
            <div>
                <label class="text-sm text-white/70 block mb-2">Título *</label>
                <input type="text"
                       name="title"
                       value="{{ old('title') }}"
                       placeholder="Ex: Estudar matemática"
                       class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/10
                              focus:outline-none focus:ring-2 focus:ring-indigo-500 text-white placeholder-white/40"
                       required>

                @error('title')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- DESCRIÇÃO -->
            <div>
                <label class="text-sm text-white/70 block mb-2">Descrição</label>
                <textarea name="description"
                          rows="4"
                          placeholder="Detalhes da tarefa..."
                          class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/10
                                 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-white placeholder-white/40">{{ old('description') }}</textarea>
            </div>

            <!-- PRIORIDADE + STATUS -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- PRIORIDADE -->
                <div>
                    <label class="text-sm text-white/70 block mb-2">Prioridade</label>

                    <div class="flex gap-2">

                        <label class="flex-1 text-center cursor-pointer">
                            <input type="radio" name="priority" value="baixa" class="hidden peer"
                                   {{ old('priority', 'media') == 'baixa' ? 'checked' : '' }}>
                            <div class="py-2 rounded-xl bg-green-500/20 text-green-300 border border-green-500/30
                                        peer-checked:bg-green-500 peer-checked:text-white transition">
                                Baixa
                            </div>
                        </label>

                        <label class="flex-1 text-center cursor-pointer">
                            <input type="radio" name="priority" value="media" class="hidden peer"
                                   {{ old('priority', 'media') == 'media' ? 'checked' : '' }}>
                            <div class="py-2 rounded-xl bg-orange-500/20 text-orange-300 border border-orange-500/30
                                        peer-checked:bg-orange-500 peer-checked:text-white transition">
                                Média
                            </div>
                        </label>

                        <label class="flex-1 text-center cursor-pointer">
                            <input type="radio" name="priority" value="alta" class="hidden peer"
                                   {{ old('priority') == 'alta' ? 'checked' : '' }}>
                            <div class="py-2 rounded-xl bg-red-500/20 text-red-300 border border-red-500/30
                                        peer-checked:bg-red-500 peer-checked:text-white transition">
                                Alta
                            </div>
                        </label>

                    </div>
                </div>

                <!-- STATUS -->
                <div>
                    <label class="text-sm text-white/70 block mb-2">Status</label>

                    <select name="status"
                            class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/10 text-white
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="pendente">Pendente</option>
                        <option value="iniciado">Iniciado</option>
                        <option value="em_andamento">Em andamento</option>
                    </select>
                </div>

            </div>

            <!-- DATA + CATEGORIA -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- DATA -->
                <div>
                    <label class="text-sm text-white/70 block mb-2">Data *</label>
                    <input type="date"
                           name="due_date"
                           value="{{ old('due_date') }}"
                           min="{{ date('Y-m-d') }}"
                           required
                           class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/10 text-white">
                </div>

                <!-- CATEGORIA -->
                <div>
                    <label class="text-sm text-white/70 block mb-2">Categoria</label>

                    <div class="flex gap-2">
                        <input type="text"
                               name="category"
                               placeholder="Trabalho"
                               class="flex-1 px-4 py-3 rounded-xl bg-white/10 border border-white/10 text-white placeholder-white/40">

                        <input type="color"
                            name="category_color"
                            value="{{ old('category_color', '#ffffff') }}"
                            class="w-12 h-12 p-0 border-0 bg-transparent cursor-pointer">
                    </div>
                </div>

            </div>

            <!-- ACTIONS -->
            <div class="flex gap-4 pt-4">

                <button type="submit"
                        class="flex-1 py-3 bg-indigo-500 hover:bg-indigo-600 rounded-xl shadow-lg transition font-medium">
                    Criar Tarefa
                </button>

                <a href="{{ route('tasks.index') }}"
                   class="flex-1 text-center py-3 bg-white/10 hover:bg-white/20 rounded-xl transition">
                    Cancelar
                </a>

            </div>

        </form>
    </div>

</div>

@endsection
