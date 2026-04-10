@extends('components.layout')

@section('title', 'Welcome')

@section('content')

<div class="w-full max-w-lg bg-white/90 backdrop-blur p-8 rounded-2xl shadow-xl text-center space-y-6">
    <h2 class="text-3xl font-bold">Organiza a tua vida</h2>


<p class="text-gray-500">
    Simple e intuitivo, para manter o controle das suas tarefas diárias com eficiência.
</p>

<div class="flex flex-col sm:flex-row gap-3 justify-center">
    <a href="/register" class="w-full sm:w-auto px-4 py-2 bg-black text-white rounded-lg text-center">
        Registrar
    </a>

    <a href="/login" class="w-full sm:w-auto px-4 py-2 border rounded-lg text-center">
        Login
    </a>
</div>


</div>
@endsection
