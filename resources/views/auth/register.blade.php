@extends('components.layout')

@section('title', 'Register')

@section('content')
<div class="w-full max-w-md bg-white/90 backdrop-blur-lg rounded-2xl shadow-xl p-8">

    <h2 class="text-2xl font-bold text-center mb-6">Criar Conta</h2>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
    @csrf

        <div>
            <label class="text-sm font-medium">Name</label>
            <input type="text" name="name"
                   class="w-full mt-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-black"
                   required>
        </div>

        <div>
            <label class="text-sm font-medium">Email</label>
            <input type="email" name="email"
                   class="w-full mt-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-black"
                   required>
        </div>

        <div>
            <label class="text-sm font-medium">Password</label>
            <input type="password" name="password"
                   class="w-full mt-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-black"
                   required>
        </div>

        <div>
            <label class="text-sm font-medium">Confirmar Password</label>
            <input type="password" name="password_confirmation"
                   class="w-full mt-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-black"
                   required>
        </div>

        <button type="submit" class="w-full bg-black text-white py-2 rounded-lg hover:opacity-90">
    Register
</button>
    </form>

    <p class="text-sm text-center mt-4">
       Já tem uma conta?
        <a href="/login" class="text-black font-semibold">Login</a>
    </p>

</div>
@endsection
