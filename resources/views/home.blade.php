@extends('components.layout')

@section('title', 'Welcome')

@section('content')

<div class="min-h-[75vh] flex items-center justify-center px-4">

    <div class="w-full max-w-3xl text-center space-y-8">

        <!--  HEADLINE -->
        <div class="space-y-4">
            <h1 class="text-4xl sm:text-5xl font-bold leading-tight">
                Organiza a tua vida

            </h1>

            <p class="text-white/70 max-w-xl mx-auto">
                Uma forma simples e moderna de gerir as tuas tarefas diárias,
                manter o foco e nunca mais esquecer o que importa.
            </p>
        </div>

        <!--  ACTION BUTTONS -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">

            <a href="/register"
               class="px-6 py-3 bg-indigo-500 hover:bg-indigo-600 rounded-xl shadow-lg transition font-medium">
                Começar agora
            </a>

            <a href="/login"
               class="px-6 py-3 bg-white/10 hover:bg-white/20 border border-white/20 rounded-xl transition">
                Já tenho conta
            </a>

        </div>

        <!--  FEATURE CARDS -->
        <div class="grid sm:grid-cols-3 gap-4 pt-6">

            <div class="bg-white/10 backdrop-blur-lg p-4 rounded-xl border border-white/10">
                <h3 class="font-semibold mt-2">Rápido</h3>
                <p class="text-sm text-white/60">Cria tarefas em segundos</p>
            </div>

            <div class="bg-white/10 backdrop-blur-lg p-4 rounded-xl border border-white/10">
                <h3 class="font-semibold mt-2">Organizado</h3>
                <p class="text-sm text-white/60">Mantém tudo no sítio certo</p>
            </div>

            <div class="bg-white/10 backdrop-blur-lg p-4 rounded-xl border border-white/10">
                <h3 class="font-semibold mt-2">Foco</h3>
                <p class="text-sm text-white/60">Prioriza o que importa</p>
            </div>

        </div>

    </div>

</div>

@endsection
