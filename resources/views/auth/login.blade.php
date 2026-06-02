@extends('layouts.app')

@section('title', 'Login - MedLogix')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 p-8 transform transition hover:scale-[1.01] duration-300">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-3xl mb-4">
                <i class="fas fa-pills"></i>
            </div>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">MedLogix</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-2 text-sm">Pharmacist Inventory Management System</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 text-red-700 dark:text-red-400 rounded-lg">
                <div class="flex">
                    <div class="py-1"><i class="fas fa-exclamation-triangle mr-2"></i></div>
                    <div>
                        @foreach ($errors->all() as $error)
                            <p class="text-xs font-semibold">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('login.submit') }}" class="space-y-5">
            @csrf

            <div>
                <label for="login" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">Username atau Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 dark:text-gray-500">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <input 
                        id="login" 
                        type="text" 
                        name="login" 
                        value="{{ old('login') }}"
                        required 
                        autofocus
                        class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 text-sm"
                        placeholder="Masukkan username atau email"
                    >
                </div>
            </div>

            <div>
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 dark:text-gray-500">
                        <i class="fas fa-lock"></i>
                    </div>
                    <input 
                        id="password" 
                        type="password" 
                        name="password" 
                        required
                        class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 text-sm"
                        placeholder="Masukkan password Anda"
                    >
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input 
                        id="remember" 
                        type="checkbox" 
                        name="remember"
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded dark:border-gray-600 dark:bg-gray-700"
                    >
                    <label for="remember" class="ml-2 block text-xs text-gray-700 dark:text-gray-300 font-medium select-none">
                        Ingat saya
                    </label>
                </div>
            </div>

            <button 
                type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-4 rounded-lg shadow-md hover:shadow-lg transition duration-200 text-sm flex items-center justify-center"
            >
                <i class="fas fa-sign-in-alt mr-2"></i> Masuk
            </button>
        </form>

        <div class="mt-6 text-center border-t border-gray-100 dark:border-gray-700 pt-6">
            <p class="text-gray-600 dark:text-gray-400 text-xs">
                Belum punya akun apoteker?
                <a href="{{ route('register') }}" class="text-blue-600 dark:text-blue-400 hover:underline font-bold">
                    Daftar di sini
                </a>
            </p>
        </div>

        <!-- Demo Accounts Alert -->
        <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800/30 rounded-xl">
            <p class="text-xs font-bold text-blue-800 dark:text-blue-300 mb-2 flex items-center">
                <i class="fas fa-info-circle mr-1.5"></i> Akun Uji Coba Apoteker (Password: <span class="underline ml-0.5">password123</span>):
            </p>
            <ul class="text-[11px] text-gray-600 dark:text-gray-400 space-y-1">
                <li><span class="font-bold text-gray-800 dark:text-gray-300">Balya:</span> username: <code class="bg-gray-100 dark:bg-gray-700 px-1 rounded font-mono text-[10px]">balya</code> / email: <code class="bg-gray-100 dark:bg-gray-700 px-1 rounded font-mono text-[10px]">balya@medlogix.com</code></li>
                <li><span class="font-bold text-gray-800 dark:text-gray-300">Farah:</span> username: <code class="bg-gray-100 dark:bg-gray-700 px-1 rounded font-mono text-[10px]">farah</code> / email: <code class="bg-gray-100 dark:bg-gray-700 px-1 rounded font-mono text-[10px]">farah@medlogix.com</code></li>
                <li><span class="font-bold text-gray-800 dark:text-gray-300">Gunawan:</span> username: <code class="bg-gray-100 dark:bg-gray-700 px-1 rounded font-mono text-[10px]">gunawan</code> / email: <code class="bg-gray-100 dark:bg-gray-700 px-1 rounded font-mono text-[10px]">gunawan@medlogix.com</code></li>
            </ul>
        </div>
    </div>
</div>
@endsection
