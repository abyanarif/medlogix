<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MedLogix - Enterprise Pharmacist Inventory')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script>
        // Initialize dark mode from localStorage
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
</head>
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-200 min-h-screen flex flex-col">
    <!-- Navigation -->
    <nav class="sticky top-0 z-50 w-full bg-white dark:bg-gray-900 dark:text-white dark:border-gray-700 shadow-md border-b border-gray-200 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ Auth::check() ? route('dashboard') : route('login') }}" class="flex items-center space-x-2">
                        <div class="text-2xl font-black tracking-tight text-blue-600 dark:text-blue-400 flex items-center gap-2">
                            <i class="fas fa-pills animate-bounce"></i> 
                            <span>MedLogix</span>
                        </div>
                    </a>
                </div>

                <!-- Menu -->
                <div class="flex items-center space-x-2 md:space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-lg text-sm font-semibold {{ Route::currentRouteName() == 'dashboard' ? 'bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800' }} transition">
                            <i class="fas fa-chart-line mr-1"></i> Dashboard Apoteker
                        </a>
                        
                        <a href="{{ route('inventory') }}" class="px-3 py-2 rounded-lg text-sm font-semibold {{ Route::currentRouteName() == 'inventory' ? 'bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800' }} transition">
                            <i class="fas fa-boxes mr-1"></i> Kelola Inventory
                        </a>
                        
                        <a href="{{ route('stock-reminder') }}" class="px-3 py-2 rounded-lg text-sm font-semibold {{ Route::currentRouteName() == 'stock-reminder' ? 'bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800' }} transition text-nowrap">
                            <i class="fas fa-bell mr-1"></i> Stock & Reminder
                        </a>
                        
                        <a href="{{ route('logs.index') }}" class="px-3 py-2 rounded-lg text-sm font-semibold {{ Route::currentRouteName() == 'logs.index' ? 'bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800' }} transition text-nowrap">
                            <i class="fas fa-history mr-1"></i> Log Transaksi
                        </a>
                    @endauth

                    <!-- Dark Mode Toggle -->
                    <button onclick="toggleDarkMode()" class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition" title="Toggle Dark Mode">
                        <i class="fas fa-sun dark:hidden"></i>
                        <i class="fas fa-moon hidden dark:inline"></i>
                    </button>

                    @auth
                        <div class="h-6 w-px bg-gray-200 dark:bg-gray-700 hidden md:block"></div>
                        
                        <!-- Logged in Pharmacist Detail -->
                        <span class="text-xs font-bold text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 px-2.5 py-1.5 rounded-lg hidden md:inline-flex items-center gap-1.5">
                            <i class="fas fa-user-md text-blue-500"></i> Apoteker {{ Auth::user()->name }}
                        </span>

                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-3 py-2 rounded-lg text-sm font-semibold text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                                <i class="fas fa-sign-out-alt"></i> Keluar
                            </button>
                        </form>
                    @else
                        @if (Route::currentRouteName() !== 'login')
                            <a href="{{ route('login') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold shadow-md hover:shadow-lg transition">
                                <i class="fas fa-sign-in-alt mr-1.5"></i> Login Apoteker
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="flex-grow py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full pt-10">
        <!-- Flash Messages -->
        @if ($message = Session::get('success'))
            <div class="mb-6 bg-emerald-50 dark:bg-emerald-950/20 border-l-4 border-emerald-500 text-emerald-800 dark:text-emerald-400 p-4 rounded-xl shadow-sm transition" role="alert">
                <div class="flex items-center">
                    <div class="py-1"><i class="fas fa-check-circle mr-3 text-lg text-emerald-500"></i></div>
                    <div class="text-sm font-semibold">{{ $message }}</div>
                </div>
            </div>
        @endif

        @if ($message = Session::get('error'))
            <div class="mb-6 bg-red-50 dark:bg-red-950/20 border-l-4 border-red-500 text-red-800 dark:text-red-400 p-4 rounded-xl shadow-sm transition" role="alert">
                <div class="flex items-center">
                    <div class="py-1"><i class="fas fa-exclamation-circle mr-3 text-lg text-red-500"></i></div>
                    <div class="text-sm font-semibold">{{ $message }}</div>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white dark:bg-gray-950 text-gray-500 dark:text-gray-400 border-t border-gray-100 dark:border-gray-800 transition-colors py-6 mt-12 text-center text-xs font-semibold">
        <div class="max-w-7xl mx-auto px-4">
            <p class="mb-1">&copy; 2026 <span class="font-bold text-blue-600 dark:text-blue-400">MedLogix Enterprise</span>. Pharmacist Inventory Management System.</p>
            @auth
                <p class="text-[10px] text-gray-400 dark:text-gray-600 mt-1 flex justify-center items-center gap-3">
                    <span><i class="fas fa-store mr-1"></i> {{ Auth::user()->apotek_address }}</span>
                    <span>&bull;</span>
                    <span><i class="fas fa-id-card mr-1"></i> SIPA: {{ Auth::user()->sipa }}</span>
                </p>
            @endauth
        </div>
    </footer>

    <script>
        function toggleDarkMode() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        }
    </script>
</body>
</html>
