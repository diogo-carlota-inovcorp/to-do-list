<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'TaskFlow')</title>
    @vite('resources/css/app.css')
</head>

<body class="min-h-screen flex flex-col text-white">

<!-- BACKGROUND -->
<div class="fixed inset-0 -z-10">
    <!-- Image -->
    <div class="absolute inset-0 bg-cover bg-center"
         style="background-image: url('https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=1920&q=80');">
    </div>

    <!-- Gradient overlay -->
    <div class="absolute inset-0 bg-gradient-to-br from-black/80 via-black/60 to-indigo-900/70"></div>

    <!-- Glow effect -->
    <div class="absolute top-0 left-0 w-96 h-96 bg-indigo-500/30 blur-3xl rounded-full"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-purple-500/30 blur-3xl rounded-full"></div>
</div>


<!--  NAVBAR -->
<nav class="bg-white/10 backdrop-blur-xl border-b border-white/10 shadow-lg">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">

        <!-- Logo -->
        @auth
    <a href="{{ route('tasks.index') }}" class="text-xl font-semibold tracking-wide">
        Tarefas
    </a>
@else
    <a href="/" class="text-xl font-semibold tracking-wide">
        Tarefas
    </a>
@endauth

        <!-- Right side -->
        <div class="flex items-center gap-4">

            @auth
                <span class="text-sm text-white/80">
                    Olá, {{ Auth::user()->name }}
                </span>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        class="bg-rose-500/90 hover:bg-rose-600 px-4 py-2 rounded-xl transition shadow-lg">
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}"
                   class="text-white/80 hover:text-white transition">
                    Login
                </a>

                <a href="{{ route('register') }}"
                   class="bg-indigo-500 hover:bg-indigo-600 px-4 py-2 rounded-xl transition shadow-lg">
                    Registrar
                </a>
            @endauth

        </div>
    </div>
</nav>

@auth


<!-- NOTIFICAÇÕES -->
<div class="fixed top-20 left-4 z-50">
    <div class="relative" id="notifWrapper">

        <!-- Bell -->
        <button id="notifButton"
            class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur-lg border border-white/20
                   flex items-center justify-center shadow-xl hover:scale-105 active:scale-95 transition">

            🔔

            @if($notificationsCount > 0)
                <span class="absolute top-1 right-1 w-3 h-3 bg-red-500 rounded-full"></span>
            @endif
        </button>

        <!-- Dropdown -->
        <div id="notificationsDropdown"
            class="absolute left-0 mt-3 w-80 bg-white/10 backdrop-blur-xl rounded-2xl p-4 shadow-2xl
                   opacity-0 scale-95 translate-y-2 pointer-events-none
                   transition-all duration-200 origin-top-left">

            <div class="flex justify-between items-center mb-3">
                <h4 class="font-semibold text-sm">Notificações</h4>
            </div>

            <div class="max-h-64 overflow-y-auto space-y-2">

                @forelse($notifications as $notif)
                    <div class="p-2 text-sm rounded-lg bg-white/10 border border-white/10">
                        {{ $notif }}
                    </div>
                @empty
                    <p class="text-white/60 text-sm text-center py-2">
                        Sem notificações
                    </p>
                @endforelse

            </div>
        </div>
    </div>
</div>
@endauth

<!-- MAIN CONTENT -->
<main class="flex-1 px-6 py-6">
    @yield('content')
</main>


<!--  FOOTER -->
<footer class="text-center text-sm text-white/50 py-4">
    © {{ date('Y') }} Tarefas. Todos os direitos reservados.
</footer>


<!--  SCRIPT -->
<script>
const button = document.getElementById('notifButton');
const dropdown = document.getElementById('notificationsDropdown');
const wrapper = document.getElementById('notifWrapper');

let isOpen = false;

function openDropdown() {
    dropdown.classList.remove('opacity-0','scale-95','translate-y-2','pointer-events-none');
    dropdown.classList.add('opacity-100','scale-100','translate-y-0');
    isOpen = true;
}

function closeDropdown() {
    dropdown.classList.add('opacity-0','scale-95','translate-y-2','pointer-events-none');
    dropdown.classList.remove('opacity-100','scale-100','translate-y-0');
    isOpen = false;
}

button.addEventListener('click', (e) => {
    e.stopPropagation();
    isOpen ? closeDropdown() : openDropdown();
});

document.addEventListener('click', (e) => {
    if (!wrapper.contains(e.target)) closeDropdown();
});

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeDropdown();
});
</script>

@stack('scripts')

</body>
</html>
