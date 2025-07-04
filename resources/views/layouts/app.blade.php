<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="drawer lg:drawer-open min-h-screen">
        <input id="drawer-toggle" type="checkbox" class="drawer-toggle" />

        <!-- Page Content -->
        <div class="drawer-content flex flex-col">
            <!-- Top Navbar -->
            <div class="w-full navbar bg-base-100 shadow-md px-4">
                <div class="flex-none lg:hidden">
                    <label for="drawer-toggle" class="btn btn-square btn-ghost">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </label>
                </div>
                <div class="flex-1 text-xl font-semibold">
                    @yield('title', 'Dashboard')
                </div>
                <div class="flex justify-end p-4">
                    <label class="cursor-pointer label gap-2">
                        <input type="checkbox" class="toggle theme-controller toggle-neutral" id="themeToggle" />
                    </label>
                </div>
            </div>

            <main class="p-4 flex-1">
                @yield('content')
            </main>
        </div>

        <!-- Sidebar -->
        @include('layouts.sidebar')
    </div>
    @yield('scripts') @stack('scripts')

    @if(session('success'))
        <div class="toast toast-top toast-end">
            <div class="alert alert-success">
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="toast toast-top toast-end">
            <div class="alert alert-error">
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <script>
        // Cek localStorage saat halaman pertama dimuat
        document.addEventListener("DOMContentLoaded", () => {
            const toggle = document.getElementById('themeToggle');
            const currentTheme = localStorage.getItem('theme') || 'light';

            // Set awal
            document.documentElement.setAttribute('data-theme', currentTheme);
            toggle.checked = currentTheme === 'dark';

            // Toggle handler
            toggle.addEventListener('change', function () {
                const theme = this.checked ? 'dark' : 'light';
                document.documentElement.setAttribute('data-theme', theme);
                localStorage.setItem('theme', theme);
            });
        });
    </script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
