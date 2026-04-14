@extends('components.layout')

@section('title', 'Nova Tarefa')

@section('content')
<div class="w-full max-w-2xl bg-white/90 backdrop-blur-lg rounded-2xl shadow-xl p-6">
    <h2 class="text-2xl font-bold mb-4">Nova Tarefa</h2>

    <form method="POST" action="{{ route('tasks.store') }}" class="space-y-4">
        @csrf

        <!-- Linha 1: Título -->
        <div>
            <label class="text-sm font-medium block mb-1">Título *</label>
            <input type="text"
                   name="title"
                   value="{{ old('title') }}"
                   placeholder="Digite o título da tarefa"
                   class="w-full px-4 py-2 border rounded-lg @error('title') border-red-500 @enderror"
                   required>
            @error('title')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Linha 2: Descrição -->
        <div>
            <label class="text-sm font-medium block mb-1">Descrição</label>
            <textarea name="description"
                      rows="3"
                      placeholder="Descreva sua tarefa..."
                      class="w-full px-4 py-2 border rounded-lg">{{ old('description') }}</textarea>
        </div>

        <!-- Linha 3: Prioridade e Status (2 colunas) -->
        <div class="grid grid-cols-2 gap-4">
            <!-- Prioridade -->
            <div>
                <label class="text-sm font-medium block mb-2">Prioridade</label>
                <div class="flex gap-3">
                    <label class="flex items-center gap-1 cursor-pointer">
                        <input type="radio" name="priority" value="baixa" {{ old('priority', 'media') == 'baixa' ? 'checked' : '' }}>
                        <span class="text-sm">Baixa</span>
                    </label>
                    <label class="flex items-center gap-1 cursor-pointer">
                        <input type="radio" name="priority" value="media" {{ old('priority', 'media') == 'media' ? 'checked' : '' }}>
                        <span class="text-sm">Média</span>
                    </label>
                    <label class="flex items-center gap-1 cursor-pointer">
                        <input type="radio" name="priority" value="alta" {{ old('priority') == 'alta' ? 'checked' : '' }}>
                        <span class="text-sm">Alta</span>
                    </label>
                </div>
            </div>

            <!-- Status -->
            <div>
                <label class="text-sm font-medium block mb-2">Status</label>
                <select name="status" class="w-full px-3 py-1.5 border rounded-lg">
                    <option value="pendente" {{ old('status') == 'pendente' ? 'selected' : '' }}>Pendente</option>
                    <option value="iniciado" {{ old('status') == 'iniciado' ? 'selected' : '' }}> Iniciado</option>
                    <option value="em_andamento" {{ old('status') == 'em_andamento' ? 'selected' : '' }}> Em Andamento</option>
                </select>
            </div>
        </div>

        <!-- Linha 4: Data e Categoria (2 colunas) -->
        <div class="grid grid-cols-2 gap-4">
            <!-- Data de Vencimento -->
            <div>
                <label class="text-sm font-medium block mb-1">Data de Vencimento</label>
                <input type="date"
                       name="due_date"
                       value="{{ old('due_date') }}"
                       min="{{ date('Y-m-d') }}"
                       class="w-full px-4 py-2 border rounded-lg">
                @error('due_date')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Categoria -->
            <div>
                <label class="text-sm font-medium block mb-1">Categoria</label>
                <div class="flex gap-2">
                    <input type="text"
                           name="category"
                           placeholder="Ex: Trabalho"
                           value="{{ old('category') }}"
                           class="flex-1 px-3 py-2 border rounded-lg">
                    <select name="category_color" class="w-20 px-2 py-2 border rounded-lg">
                        <option value="#3B82F6" style="background-color: #3B82F6; color: white;">🔵</option>
                        <option value="#10B981" style="background-color: #10B981; color: white;">🟢</option>
                        <option value="#F59E0B" style="background-color: #F59E0B; color: white;">🟡</option>
                        <option value="#EF4444" style="background-color: #EF4444; color: white;">🔴</option>
                        <option value="#8B5CF6" style="background-color: #8B5CF6; color: white;">🟣</option>
                    </select>
                </div>
            </div>
        </div>

                <!-- Linha 5: Partilhar -->
        <div>
            <label class="text-sm font-medium block mb-1">
                Partilhar com (email)
            </label>

            <div class="flex gap-2">
                <input type="email"
                    id="shared_email_input"
                    placeholder="ex: amigo@email.com"
                    class="w-full px-3 py-2 border rounded-lg">

                <button type="button"
                        onclick="addEmail()"
                        class="px-3 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                    +
                </button>
            </div>

            <!-- Emails adicionados -->
            <div id="emailsList" class="mt-2 flex flex-wrap gap-2"></div>

            <!-- Hidden input -->
            <input type="hidden" name="shared_emails" id="sharedEmailsInput">
        </div>

        <!-- Botões -->
        <div class="flex gap-3 pt-2">
            <button type="submit" class="flex-1 bg-black text-white py-2 rounded-lg hover:opacity-90">
                Criar Tarefa
            </button>
            <a href="{{ route('tasks.index') }}" class="flex-1 text-center bg-gray-200 text-gray-700 py-2 rounded-lg hover:bg-gray-300">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
        @push('scripts')
        <script>
        let emails = [];

        function addEmail() {
            const input = document.getElementById('shared_email_input');
            const email = input.value.trim();

            if (!email) return;

            emails.push(email);
            input.value = '';

            renderEmails();
        }

        function renderEmails() {
            const container = document.getElementById('emailsList');
            const hiddenInput = document.getElementById('sharedEmailsInput');

            container.innerHTML = '';

            emails.forEach((email, index) => {
                container.innerHTML += `
                    <div class="bg-gray-200 px-2 py-1 rounded flex items-center gap-1">
                        <span class="text-sm">${email}</span>
                        <button type="button" onclick="removeEmail(${index})" class="text-red-500">×</button>
                    </div>
                `;
            });

            hiddenInput.value = JSON.stringify(emails);
        }

        function removeEmail(index) {
            emails.splice(index, 1);
            renderEmails();
        }
        </script>
        @endpush
