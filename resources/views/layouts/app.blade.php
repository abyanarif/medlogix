<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MedLogix - Enterprise Pharmacist Inventory')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-slate-50 min-h-screen flex flex-col text-slate-900">
    <!-- Navigation -->
    <nav class="sticky top-0 z-50 w-full bg-white shadow-sm border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ Auth::check() ? (auth()->user()->role === 'admin' ? route('admin.dashboard') : route('dashboard')) : route('login') }}" class="flex items-center space-x-2">
                        <div class="flex items-center">
                            <img src="{{ asset('images/logo.png') }}" class="h-8 w-auto mr-2" alt="MedLogix Logo">
                            <span class="text-xl font-bold text-teal-900">MedLogix</span>
                        </div>
                    </a>
                </div>

                <!-- Menu -->
                <div class="flex items-center space-x-2 md:space-x-3">
                    @auth
                        @if (auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-lg text-sm font-semibold {{ Route::currentRouteName() == 'admin.dashboard' ? 'bg-teal-50 text-teal-800' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }} transition whitespace-nowrap">
                                <i class="fas fa-user-shield mr-1"></i> Dashboard
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-lg text-sm font-semibold {{ Route::currentRouteName() == 'dashboard' ? 'bg-teal-50 text-teal-800' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }} transition whitespace-nowrap">
                                <i class="fas fa-chart-line mr-1"></i> Dashboard
                            </a>
                            
                            <a href="{{ route('inventory') }}" class="px-3 py-2 rounded-lg text-sm font-semibold {{ Route::currentRouteName() == 'inventory' ? 'bg-teal-50 text-teal-800' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }} transition whitespace-nowrap">
                                <i class="fas fa-boxes mr-1"></i> Inventory
                            </a>
                            
                            <a href="{{ route('stock-reminder') }}" class="px-3 py-2 rounded-lg text-sm font-semibold {{ Route::currentRouteName() == 'stock-reminder' ? 'bg-teal-50 text-teal-800' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }} transition whitespace-nowrap">
                                <i class="fas fa-bell mr-1"></i> Reminder
                            </a>
                            
                            <a href="{{ route('logs.index') }}" class="px-3 py-2 rounded-lg text-sm font-semibold {{ Route::currentRouteName() == 'logs.index' ? 'bg-teal-50 text-teal-800' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }} transition whitespace-nowrap">
                                <i class="fas fa-history mr-1"></i> Log Transaksi
                            </a>

                            <a href="{{ route('billing.index') }}" class="px-3 py-2 rounded-lg text-sm font-semibold {{ Route::currentRouteName() == 'billing.index' ? 'bg-teal-50 text-teal-800' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }} transition whitespace-nowrap">
                                <i class="fas fa-credit-card mr-1"></i> Langganan &amp; Slot
                            </a>
                        @endif
                    @endauth

                    @auth
                        <div class="h-6 w-px bg-slate-200 hidden md:block"></div>
                        
                        <!-- Logged in Detail -->
                        @if (auth()->user()->role === 'admin')
                            <span class="text-xs font-bold text-slate-700 bg-slate-100 px-2.5 py-1.5 rounded-lg hidden md:inline-flex items-center gap-1.5 whitespace-nowrap">
                                <i class="fas fa-user-shield text-teal-600"></i> Admin <span class="truncate max-w-[100px] md:max-w-[150px] inline-block align-bottom">{{ Auth::user()->name }}</span>
                            </span>
                        @else
                            <span class="text-xs font-bold text-slate-700 bg-slate-100 px-2.5 py-1.5 rounded-lg hidden md:inline-flex items-center gap-1.5 whitespace-nowrap">
                                <i class="fas fa-user-md text-teal-600"></i> Apoteker <span class="truncate max-w-[100px] md:max-w-[150px] inline-block align-bottom">{{ Auth::user()->name }}</span>
                            </span>
                        @endif

                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-3 py-2 rounded-lg text-sm font-semibold text-red-600 hover:bg-red-50 transition whitespace-nowrap">
                                <i class="fas fa-sign-out-alt"></i> Keluar
                            </button>
                        </form>
                    @else
                        @if (Route::currentRouteName() !== 'login')
                            <a href="{{ route('login') }}" class="px-4 py-2 bg-teal-900 hover:bg-teal-800 text-white rounded-lg text-sm font-semibold shadow-md hover:shadow-lg transition whitespace-nowrap">
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
            <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 p-4 rounded-xl shadow-sm transition" role="alert">
                <div class="flex items-center">
                    <div class="py-1"><i class="fas fa-check-circle mr-3 text-lg text-emerald-500"></i></div>
                    <div class="text-sm font-semibold">{{ $message }}</div>
                </div>
            </div>
        @endif

        @if ($message = Session::get('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded-xl shadow-sm transition" role="alert">
                <div class="flex items-center">
                    <div class="py-1"><i class="fas fa-exclamation-circle mr-3 text-lg text-red-500"></i></div>
                    <div class="text-sm font-semibold">{{ $message }}</div>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white text-slate-500 border-t border-slate-200 py-6 mt-12 text-center text-xs font-semibold">
        <div class="max-w-7xl mx-auto px-4">
            <p class="mb-1">&copy; 2026 <span class="font-bold text-teal-900">MedLogix Enterprise</span>. Pharmacist Inventory Management System.</p>
            @auth
                @if (auth()->user()->role !== 'admin')
                    <p class="text-[10px] text-slate-400 mt-1 flex justify-center items-center gap-3">
                        <span><i class="fas fa-store mr-1"></i> {{ Auth::user()->apotek_address }}</span>
                        <span>&bull;</span>
                        <span><i class="fas fa-id-card mr-1"></i> SIPA: {{ Auth::user()->sipa }}</span>
                    </p>
                @endif
            @endauth
        </div>
    </footer>

    @if (!empty($waLink))
        <a href="{{ $waLink }}" target="_blank" class="fixed bottom-6 right-6 z-50 bg-green-500 hover:bg-green-600 text-white rounded-full p-3 md:py-3 md:px-5 shadow-lg flex items-center gap-2 transition-transform hover:scale-105" aria-label="Hubungi Customer Support via WhatsApp">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16">
            <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/>
            </svg>
            <span class="hidden md:inline font-bold">Hubungi CS</span>
        </a>
    @endif
</body>
</html>
