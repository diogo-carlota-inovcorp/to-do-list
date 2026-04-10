@extends('components.layout')

@section('title', 'Login')

@section('content')
<div class="w-full max-w-md bg-white/90 backdrop-blur-lg rounded-2xl shadow-xl p-8">

    <h2 class="text-2xl font-bold text-center mb-6">Bem-vindo de volta</h2>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

    <div>
        <label class="text-sm font-medium">Email</label>
        <input type="email" name="email"  value="{{ old('email') }}"
               class="w-full mt-1 px-4 py-2 border rounded-lg">
    </div>

    <div>
        <label class="text-sm font-medium">Password</label>
        <input type="password"
                 name="password" 
               class="w-full mt-1 px-4 py-2 border rounded-lg">
    </div>

    <button type="submit"
            class="w-full bg-black text-white py-2 rounded-lg hover:opacity-90">
        Login
    </button>

</form>

    <p class="text-sm text-center mt-4">
      Não tem uma conta?
        <a href="/register" class="text-black font-semibold">Registrar</a>
    </p>

</div>
@endsection
