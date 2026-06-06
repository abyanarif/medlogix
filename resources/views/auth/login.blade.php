@extends('layouts.app')

@section('title', 'Login - MedLogix')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-sm border border-slate-200 p-8 transform transition hover:scale-[1.01] duration-300">
        <div class="flex flex-col items-center mb-8">
            <div class="flex items-center mb-2">
                <img src="{{ asset('images/logo.png') }}" class="h-8 w-auto mr-2" alt="MedLogix Logo">
                <span class="text-xl font-bold text-teal-900">MedLogix</span>
            </div>
            <p class="text-slate-600 text-sm text-center">Pharmacist Inventory Management System</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-lg">
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
                <label for="login" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Username atau Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <input 
                        id="login" 
                        type="text" 
                        name="login" 
                        value="{{ old('login') }}"
                        required 
                        autofocus
                        class="block w-full pl-10 pr-3 py-2.5 border border-slate-300 bg-white text-slate-900 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition duration-150 text-sm"
                        placeholder="Masukkan username atau email"
                    >
                </div>
            </div>

            <div>
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <i class="fas fa-lock"></i>
                    </div>
                    <input 
                        id="password" 
                        type="password" 
                        name="password" 
                        required
                        class="block w-full pl-10 pr-3 py-2.5 border border-slate-300 bg-white text-slate-900 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition duration-150 text-sm"
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
                        class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-slate-300 rounded"
                    >
                    <label for="remember" class="ml-2 block text-xs text-slate-600 font-medium select-none">
                        Ingat saya
                    </label>
                </div>
            </div>

            <button 
                type="submit"
                class="w-full bg-teal-900 hover:bg-teal-800 text-white font-bold py-2.5 px-4 rounded-lg shadow-md hover:shadow-lg transition duration-200 text-sm flex items-center justify-center"
            >
                <i class="fas fa-sign-in-alt mr-2"></i> Masuk
            </button>
        </form>

        <div class="mt-6 text-center border-t border-slate-100 pt-6">
            <p class="text-slate-600 text-xs">
                Belum punya akun apoteker?
                <a href="{{ route('register') }}" class="text-teal-700 hover:text-teal-900 font-bold hover:underline">
                    Daftar di sini
                </a>
            </p>
        </div>

        <!-- Demo Accounts Alert -->
        <div class="mt-6 p-4 bg-teal-50 border border-teal-200 text-teal-800 rounded-xl">
            <p class="text-xs font-bold text-teal-900 mb-2 flex items-center">
                <i class="fas fa-info-circle mr-1.5"></i> Akun Uji Coba Apoteker (Password: <span class="underline ml-0.5">password123</span>):
            </p>
            <ul class="text-[11px] text-slate-600 space-y-1">
                <li><span class="font-bold text-slate-900">Balya:</span> username: <code class="bg-white border border-teal-100 px-1 rounded font-mono text-[10px]">balya</code> / email: <code class="bg-white border border-teal-100 px-1 rounded font-mono text-[10px]">balya@medlogix.com</code></li>
                <li><span class="font-bold text-slate-900">Farah:</span> username: <code class="bg-white border border-teal-100 px-1 rounded font-mono text-[10px]">farah</code> / email: <code class="bg-white border border-teal-100 px-1 rounded font-mono text-[10px]">farah@medlogix.com</code></li>
                <li><span class="font-bold text-slate-900">Gunawan:</span> username: <code class="bg-white border border-teal-100 px-1 rounded font-mono text-[10px]">gunawan</code> / email: <code class="bg-white border border-teal-100 px-1 rounded font-mono text-[10px]">gunawan@medlogix.com</code></li>
            </ul>
        </div>
    </div>
</div>
@endsection
