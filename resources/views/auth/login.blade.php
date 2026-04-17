@extends('components.layout')

@section('title', 'Login')

@section('content')

<div class="min-h-[75vh] flex items-center justify-center px-4">

    <div class="w-full max-w-md bg-white/10 backdrop-blur-xl border border-white/10 rounded-3xl shadow-2xl p-8">

        <!-- HEADER -->
        <div class="text-center mb-8">
            <h2 class="text-3xl font-semibold">Bem-vindo de volta</h2>
            <p class="text-white/60 text-sm mt-1">
                Entra na tua conta para continuar
            </p>
        </div>

        <!-- FORM -->
        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- EMAIL -->
            <div>
                <label class="text-sm text-white/70">Email</label>
                <input type="email"
                       name="email"
                       value="{{ old('email') }}"
                       class="w-full mt-2 px-4 py-3 rounded-xl bg-white/10 border border-white/10
                              text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <!-- PASSWORD -->
            <div>
                <label class="text-sm text-white/70">Password</label>
                <input type="password"
                       name="password"
                       class="w-full mt-2 px-4 py-3 rounded-xl bg-white/10 border border-white/10
                              text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <!-- BUTTON -->
            <button type="submit"
                    class="w-full py-3 bg-indigo-500 hover:bg-indigo-600 rounded-xl shadow-lg transition font-medium">
                Login
            </button>

        </form>

        <!-- FOOTER -->
        <p class="text-sm text-center mt-6 text-white/60">
            Não tens conta?
            <a href="/register" class="text-indigo-400 hover:text-indigo-300 font-medium">
                Registar
            </a>
        </p>

    </div>

</div>

@endsection
