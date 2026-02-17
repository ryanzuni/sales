<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sales System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
</head>
<body class="bg-gray-100">

<div class="flex min-h-screen">

    {{-- Sidebar --}}
    <aside class="w-64 bg-gray-900 text-white p-6">
        <h2 class="text-xl font-bold mb-8">Sales System</h2>
        <a href="/sales" class="block bg-gray-800 p-2 rounded">Sales</a>
    </aside>

    {{-- Main --}}
    <div class="flex-1 flex flex-col">

        {{-- Topbar --}}
        <header class="bg-white shadow p-4 flex justify-between">
            <h1 class="font-semibold text-lg">Sales Dashboard</h1>
            <span>{{ now()->format('d M Y') }}</span>
        </header>

        <main class="p-6">
            {{ $slot }}
        </main>

    </div>
</div>

@livewireScripts
</body>
</html>
