<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sambal Bakar Indonesia</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- HERO ICON CDN --}}
    <script type="module">
        import { createIcons, icons } from 'https://cdn.jsdelivr.net/npm/lucide@latest/dist/esm/lucide.js';
        createIcons({ icons });
    </script>

    @livewireStyles
</head>

<body class="bg-gray-100">

<div x-data="{ open: false }" class="min-h-screen flex">

    {{-- SIDEBAR --}}
    <aside 
        class="bg-white border-r border-gray-200 w-64 flex flex-col
               fixed md:static inset-y-0 left-0 z-50
               transform transition-transform duration-300
               -translate-x-full md:translate-x-0"
        :class="{ 'translate-x-0': open }">

        <div class="p-6 border-b">
            <h2 class="text-xl font-bold text-gray-800">
                Sambal Bakar
            </h2>
            <p class="text-xs text-gray-400">Indonesia</p>
        </div>

        <nav class="flex-1 p-4 space-y-2">

            <a href="/dashboard"
               class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm transition
               {{ request()->is('dashboard') 
                    ? 'bg-gray-900 text-white' 
                    : 'text-gray-600 hover:bg-gray-100' }}">

                <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                Dashboard
            </a>

            <a href="/sales"
               class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm transition
               {{ request()->is('sales') 
                    ? 'bg-gray-900 text-white' 
                    : 'text-gray-600 hover:bg-gray-100' }}">

                <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                Sales
            </a>

        </nav>

        <div class="p-4 text-xs text-gray-400 border-t">
            © {{ date('Y') }} Sambal Bakar Indonesia
        </div>

    </aside>

    {{-- OVERLAY MOBILE --}}
    <div 
        x-show="open"
        x-transition
        @click="open = false"
        class="fixed inset-0 bg-black/40 z-40 md:hidden">
    </div>

    {{-- CONTENT --}}
    <div class="flex-1 flex flex-col">

        {{-- TOPBAR --}}
        <header class="bg-white border-b px-6 py-4 flex justify-between items-center">

            <div class="flex items-center gap-4">

                {{-- MOBILE BUTTON --}}
                <button @click="open = true" class="md:hidden">
                    <i data-lucide="menu" class="w-5 h-5 text-gray-600"></i>
                </button>

                <h1 class="font-semibold text-gray-700">
                    {{ request()->is('dashboard') ? 'Dashboard' : 'Sales Management' }}
                </h1>

            </div>

            <span class="text-sm text-gray-400">
                {{ now()->format('d M Y') }}
            </span>

        </header>

        <main class="p-6 md:p-10 flex-1">
            {{ $slot }}
        </main>

    </div>

</div>

@livewireScripts
</body>
</html>
