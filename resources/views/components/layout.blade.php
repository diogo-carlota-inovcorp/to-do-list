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

    <div class="absolute inset-0 bg-black/40"></div>
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

<!-- Background Script -->
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


</body>
</html>
