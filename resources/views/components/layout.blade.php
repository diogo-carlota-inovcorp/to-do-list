<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'TaskFlow')</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen flex flex-col overflow-hidden">


<!-- Background -->
<div id="bg-wrapper" class="fixed inset-0 -z-10 overflow-hidden">
    <div id="bg1" class="absolute inset-0 bg-cover bg-center transition-opacity duration-1000 opacity-100"></div>
    <div id="bg2" class="absolute inset-0 bg-cover bg-center transition-opacity duration-1000 opacity-0"></div>

    <div class="absolute inset-0 bg-cover bg-center"
     style="background-image: url('https://i.pinimg.com/736x/82/1e/67/821e67f51453df5b7741e3f5480a96b1.jpg');">
</div>
</div>

<!-- Navbar -->
 <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
            <div class="text-xl font-bold">
                <a href="/">Task Manager</a>
            </div>

            <div class="flex gap-4 items-center">
                @auth
                    <!-- Mostrar quando usuário está logado -->
                    <span class="text-sm text-gray-600">
                        Olá, {{ Auth::user()->name }}!
                    </span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit"
                                class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                            Logout
                        </button>
                    </form>
                @else
                    <!-- Mostrar quando NÃO está logado -->
                    <a href="{{ route('login') }}"
                       class="text-gray-700 hover:text-black">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                       class="bg-black text-white px-4 py-2 rounded-lg hover:opacity-90">
                        Registrar
                    </a>
                @endauth
            </div>
        </div>
    </nav>


<!-- Page Content -->
<main class="flex-1 flex items-center justify-center px-4">
    @yield('content')
</main>

<!-- Footer -->
<footer class="text-center text-sm text-gray-200 py-4">
    © {{ date('Y') }} TaskDone. All rights reserved.
</footer>



<<div class="fixed top-4 left-4 z-50">
    <div class="relative" id="notifWrapper">

        <!-- BELL BUTTON -->
        <button
            id="notifButton"
            class="w-12 h-12 rounded-xl border-2 border-black flex items-center justify-center
                   hover:bg-gray-100 transition shadow-md active:scale-95 relative"
        >
            🔔

            @if($notificationsCount > 0)
                <span class="absolute top-1 right-1 w-3 h-3 bg-red-500 rounded-full"></span>
            @endif
        </button>

        <!-- DROPDOWN -->
        <div
    id="notificationsDropdown"
    class="absolute left-0 mt-3 w-80 bg-white rounded-xl shadow-xl p-3 z-50
           opacity-0 scale-95 translate-y-2 pointer-events-none
           transition-all duration-200 origin-top-left"
>
    <div class="flex justify-between items-center mb-2">
        <h4 class="font-semibold text-sm">Notificações</h4>

        <!-- CLEAR ALL -->
        <form method="POST" action="/notifications/clear">
    @csrf
    <button class="text-xs text-gray-500 hover:text-black">
        Clear all
    </button>
</form>
    </div>

    <div class="max-h-64 overflow-y-auto space-y-2">

        @forelse($notifications as $notif)

            <div class="p-2 text-sm border rounded-lg bg-gray-50 space-y-2">

                <!-- MESSAGE (safe check) -->
                <div>
                    {{ is_array($notif) ? $notif['message'] : $notif }}
                </div>

                <!-- ACTIONS ONLY FOR INVITES -->
                @if(is_array($notif) && ($notif['type'] ?? null) === 'task_invite')
                    <div class="flex gap-2">

                        <!-- ACCEPT -->
                        <form method="POST" action="/invites/accept">
                            @csrf
                            <input type="hidden" name="notification_id" value="{{ $notif['id'] }}">
                            <input type="hidden" name="task_id" value="{{ $notif['task_id'] }}">

                            <button class="px-2 py-1 bg-green-500 text-white rounded text-xs">
                                Accept
                            </button>
                        </form>

                        <!-- REJECT -->
                        <form method="POST" action="/invites/reject">
                            @csrf
                            <input type="hidden" name="notification_id" value="{{ $notif['id'] }}">
                            <input type="hidden" name="task_id" value="{{ $notif['task_id'] }}">

                            <button class="px-2 py-1 bg-red-500 text-white rounded text-xs">
                                Ignore
                            </button>
                        </form>

                    </div>
                @endif

            </div>

        @empty
            <p class="text-gray-500 text-sm text-center py-2">
                Sem notificações
            </p>
        @endforelse

    </div>
</div>

<!-- Background Script -->
<!--
<script>
const images = [
    "https://images.unsplash.com/photo-1492724441997-5dc865305da7",
    "https://images.unsplash.com/photo-1500530855697-b586d89ba3ee",
    "https://images.unsplash.com/photo-1496307042754-b4aa456c4a2d"
];

let index = 0;

const layer1 = document.getElementById("bg1");
const layer2 = document.getElementById("bg2");

layer1.style.backgroundImage = `url(${images[0]})`;
layer2.style.backgroundImage = `url(${images[1]})`;

let showingFirst = true;

function changeBackground() {
    index = (index + 1) % images.length;
    const nextImage = images[index];

    if (showingFirst) {
        layer2.style.backgroundImage = `url(${nextImage})`;
        layer2.classList.remove("opacity-0");
        layer2.classList.add("opacity-100");

        layer1.classList.remove("opacity-100");
        layer1.classList.add("opacity-0");
    } else {
        layer1.style.backgroundImage = `url(${nextImage})`;
        layer1.classList.remove("opacity-0");
        layer1.classList.add("opacity-100");

        layer2.classList.remove("opacity-100");
        layer2.classList.add("opacity-0");
    }

    showingFirst = !showingFirst;
}

setInterval(changeBackground, 5000);
</script>
-->
<script>
const button = document.getElementById('notifButton');
const dropdown = document.getElementById('notificationsDropdown');
const wrapper = document.getElementById('notifWrapper');

let isOpen = false;

function openDropdown() {
    dropdown.classList.remove(
        'opacity-0',
        'scale-95',
        'translate-y-2',
        'pointer-events-none'
    );

    dropdown.classList.add(
        'opacity-100',
        'scale-100',
        'translate-y-0'
    );

    isOpen = true;
}

function closeDropdown() {
    dropdown.classList.add(
        'opacity-0',
        'scale-95',
        'translate-y-2',
        'pointer-events-none'
    );

    dropdown.classList.remove(
        'opacity-100',
        'scale-100',
        'translate-y-0'
    );

    isOpen = false;
}

button.addEventListener('click', (e) => {
    e.stopPropagation();
    isOpen ? closeDropdown() : openDropdown();
});

// click outside closes it
document.addEventListener('click', (e) => {
    if (!wrapper.contains(e.target)) {
        closeDropdown();
    }
});

// ESC key closes it
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeDropdown();
});
</script>

@stack('scripts')
</body>
</html>
