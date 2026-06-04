@extends('layouts.app')

@section('title', 'Daftar Apoteker - MedLogix')

@section('content')
<div class="min-h-[85vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-xl w-full bg-white rounded-2xl shadow-sm border border-slate-200 p-8 transform transition hover:scale-[1.005] duration-300">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-teal-50 border border-teal-200 text-teal-800 text-3xl mb-4">
                <i class="fas fa-user-plus text-teal-600"></i>
            </div>
            <h1 class="text-3xl font-extrabold text-teal-900">Registrasi Apoteker</h1>
            <p class="text-slate-600 mt-2 text-sm">Bergabung dengan MedLogix Inventory Management</p>
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

        <form method="POST" action="{{ route('register.submit') }}" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Nama Lengkap -->
                <div>
                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Nama Lengkap</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i class="fas fa-id-card"></i>
                        </div>
                        <input 
                            id="name" 
                            type="text" 
                            name="name" 
                            value="{{ old('name') }}"
                            required 
                            class="block w-full pl-10 pr-3 py-2 border border-slate-300 bg-white text-slate-900 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition text-sm"
                            placeholder="Apoteker Nama"
                        >
                    </div>
                </div>

                <!-- Username -->
                <div>
                    <label for="username" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i class="fas fa-at"></i>
                        </div>
                        <input 
                            id="username" 
                            type="text" 
                            name="username" 
                            value="{{ old('username') }}"
                            required 
                            class="block w-full pl-10 pr-3 py-2 border border-slate-300 bg-white text-slate-900 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition text-sm"
                            placeholder="username"
                        >
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <input 
                            id="email" 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}"
                            required 
                            class="block w-full pl-10 pr-3 py-2 border border-slate-300 bg-white text-slate-900 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition text-sm"
                            placeholder="apoteker@mail.com"
                        >
                    </div>
                </div>

                <!-- Nomor Handphone -->
                <div>
                    <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Nomor Handphone</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i class="fas fa-phone"></i>
                        </div>
                        <input 
                            id="phone" 
                            type="text" 
                            name="phone" 
                            value="{{ old('phone') }}"
                            required 
                            class="block w-full pl-10 pr-3 py-2 border border-slate-300 bg-white text-slate-900 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition text-sm"
                            placeholder="0812XXXXXXXX"
                        >
                    </div>
                </div>
            </div>

            <!-- SIPA (Surat Izin Praktik Apoteker) -->
            <div>
                <label for="sipa" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Nomor SIPA</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <i class="fas fa-file-medical"></i>
                    </div>
                    <input 
                        id="sipa" 
                        type="text" 
                        name="sipa" 
                        value="{{ old('sipa') }}"
                        required 
                        class="block w-full pl-10 pr-3 py-2 border border-slate-300 bg-white text-slate-900 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition text-sm"
                        placeholder="SIPA-12345/XXXX/XXXX"
                    >
                </div>
            </div>

            <!-- Alamat Apotek -->
            <div>
                <label for="apotek_address" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Alamat Apotek</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 pt-2 text-slate-400">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <textarea 
                        id="apotek_address" 
                        name="apotek_address" 
                        rows="2"
                        required
                        class="block w-full pl-10 pr-3 py-2 border border-slate-300 bg-white text-slate-900 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition text-sm"
                        placeholder="Tuliskan alamat lengkap apotek Anda"
                    >{{ old('apotek_address') }}</textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Password -->
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
                            class="block w-full pl-10 pr-3 py-2 border border-slate-300 bg-white text-slate-900 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition text-sm"
                            placeholder="Min. 6 karakter"
                        >
                    </div>
                </div>

                <!-- Konfirmasi Password -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Konfirmasi Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i class="fas fa-check-double"></i>
                        </div>
                        <input 
                            id="password_confirmation" 
                            type="password" 
                            name="password_confirmation" 
                            required
                            class="block w-full pl-10 pr-3 py-2 border border-slate-300 bg-white text-slate-900 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition text-sm"
                            placeholder="Ketik ulang password"
                        >
                    </div>
                </div>
            </div>

            <button 
                type="submit"
                class="w-full bg-teal-900 hover:bg-teal-800 text-white font-bold py-2.5 px-4 rounded-lg shadow-md hover:shadow-lg transition duration-200 text-sm flex items-center justify-center mt-2"
            >
                <i class="fas fa-user-plus mr-2"></i> Daftar Sebagai Apoteker
            </button>
        </form>

        <div class="mt-6 text-center border-t border-slate-100 pt-6">
            <p class="text-slate-600 text-xs">
                Sudah terdaftar sebagai apoteker?
                <a href="{{ route('login') }}" class="text-teal-700 hover:text-teal-900 font-bold hover:underline">
                    Login di sini
                </a>
            </p>
        </div>
    </div>
</div>
@endsection
