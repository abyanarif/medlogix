@extends('layouts.app')

@section('title', 'Dashboard Apoteker - MedLogix')

@section('content')
<!-- Header Pharmacist Details Card -->
<div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-3xl p-6 md:p-8 text-white shadow-lg mb-8 relative overflow-hidden">
    <div class="absolute right-0 top-0 translate-x-8 -translate-y-8 opacity-10 text-9xl">
        <i class="fas fa-prescription-bottle-medical"></i>
    </div>
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
        <div>
            <span class="bg-blue-500/30 text-blue-100 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                <i class="fas fa-check-circle mr-1"></i> Apoteker Terverifikasi
            </span>
            <h1 class="text-3xl font-black mt-2">Apotek Apoteker {{ Auth::user()->name }}</h1>
            <p class="text-blue-100 text-sm mt-1 flex flex-wrap items-center gap-4">
                <span><i class="fas fa-id-card mr-1 text-blue-300"></i> SIPA: <strong class="text-white">{{ Auth::user()->sipa }}</strong></span>
                <span class="hidden md:inline">&bull;</span>
                <span><i class="fas fa-map-marker-alt mr-1 text-blue-300"></i> {{ Auth::user()->apotek_address }}</span>
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('inventory') }}" class="bg-white hover:bg-blue-50 text-blue-600 font-bold px-5 py-2.5 rounded-xl transition shadow-md hover:shadow-lg text-sm flex items-center gap-1.5">
                <i class="fas fa-plus-circle"></i> Input Obat Baru
            </a>
            <a href="{{ route('stock-reminder') }}" class="bg-blue-500/30 hover:bg-blue-500/50 text-white font-bold px-5 py-2.5 rounded-xl transition text-sm flex items-center gap-1.5">
                <i class="fas fa-bell"></i> Alerts ({{ $alertCount }})
            </a>
        </div>
    </div>
</div>

<!-- Stats Dashboard Overview -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
    <!-- Stat 1: Total Medicines -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-100 dark:border-gray-700 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center text-2xl">
            <i class="fas fa-box-open"></i>
        </div>
        <div>
            <p class="text-gray-400 dark:text-gray-500 text-xs font-bold uppercase tracking-wider">Total Jenis Obat</p>
            <h3 class="text-2xl font-black text-gray-900 dark:text-white mt-0.5">{{ $medicines->count() }}</h3>
        </div>
    </div>

    <!-- Stat 2: Total Sales/Outflow -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-100 dark:border-gray-700 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-2xl">
            <i class="fas fa-shopping-basket"></i>
        </div>
        <div>
            <p class="text-gray-400 dark:text-gray-500 text-xs font-bold uppercase tracking-wider">Simulasi Obat Keluar</p>
            <h3 class="text-2xl font-black text-gray-900 dark:text-white mt-0.5">{{ $medicines->sum('obat_keluar') }} <span class="text-xs text-gray-400 font-medium">butir</span></h3>
        </div>
    </div>

    <!-- Stat 3: Restock Warning Settings -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-100 dark:border-gray-700 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 flex items-center justify-center text-2xl">
            <i class="fas fa-triangle-exclamation"></i>
        </div>
        <div>
            <p class="text-gray-400 dark:text-gray-500 text-xs font-bold uppercase tracking-wider">Restock Alert Threshold</p>
            <h3 class="text-2xl font-black text-gray-900 dark:text-white mt-0.5">
                Stok &le; {{ $userNotification->batas_minimal_stok ?? 10 }}
            </h3>
        </div>
    </div>
</div>

<!-- Dashboard Apoteker: Summary Drug Sales & Outflow List -->
<div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden mb-8">
    <div class="p-6 md:p-8 border-b border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row justify-between sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-black text-gray-950 dark:text-white flex items-center gap-2">
                <i class="fas fa-clipboard-list text-blue-600"></i> Ringkasan Aliran & Informasi Obat
            </h2>
            <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">Daftar obat-obatan beserta simulasi volume keluar/pembelian dan info penanganan.</p>
        </div>
        <span class="text-xs font-bold text-gray-400 dark:text-gray-500 flex items-center gap-1.5">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span> Update: Realtime
        </span>
    </div>

    <!-- Responsive Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 dark:bg-gray-900/40 text-gray-400 dark:text-gray-500 text-xs font-bold uppercase tracking-wider border-b border-gray-100 dark:border-gray-700">
                    <th class="py-4 px-6">Nama Obat &amp; Brand</th>
                    <th class="py-4 px-6 text-center">Harga Satuan</th>
                    <th class="py-4 px-6">Volume Keluar / Pembelian</th>
                    <th class="py-4 px-6">Informasi General / Alert</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700 text-sm">
                @foreach ($medicines as $medicine)
                    <tr class="hover:bg-gray-50/40 dark:hover:bg-gray-900/10 transition">
                        <!-- Medicine Name & Brand -->
                        <td class="py-4.5 px-6">
                            <div class="font-bold text-gray-900 dark:text-white">{{ $medicine->nama_obat }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 flex items-center gap-1">
                                <i class="fas fa-tags text-[10px] text-blue-500/80"></i> {{ $medicine->brand }}
                            </div>
                        </td>

                        <!-- Unit Price -->
                        <td class="py-4.5 px-6 text-center">
                            <span class="font-mono font-bold text-gray-900 dark:text-white bg-gray-100 dark:bg-gray-700 px-2.5 py-1.5 rounded-lg text-xs">
                                Rp {{ number_format($medicine->harga, 0, ',', '.') }}
                            </span>
                        </td>

                        <!-- Simulated Drug Sales/Outflow Volume -->
                        <td class="py-4.5 px-6">
                            <div class="flex items-center gap-3 max-w-[200px]">
                                <div class="flex-grow bg-gray-100 dark:bg-gray-700 rounded-full h-2">
                                    @php
                                        // Calculate percentage for progress bars relative to a base of 200 units max
                                        $percent = min(100, round(($medicine->obat_keluar / 200) * 100));
                                    @endphp
                                    <div class="bg-emerald-500 h-2 rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
                                </div>
                                <span class="font-bold text-gray-700 dark:text-gray-300 text-xs text-nowrap">
                                    {{ $medicine->obat_keluar }} <span class="text-[10px] text-gray-400 font-medium">pcs</span>
                                </span>
                            </div>
                        </td>

                        <!-- General Information Warning alerts -->
                        <td class="py-4.5 px-6">
                            @if (str_contains(strtolower($medicine->informasi_general), 'hati'))
                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-rose-700 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/20 px-3 py-1.5 rounded-lg border border-rose-100 dark:border-rose-900/30">
                                    <i class="fas fa-triangle-exclamation text-rose-500"></i> {{ str_replace('Alert:', '', $medicine->informasi_general) }}
                                </span>
                            @elseif (str_contains(strtolower($medicine->informasi_general), 'habiskan'))
                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/20 px-3 py-1.5 rounded-lg border border-amber-100 dark:border-amber-900/30">
                                    <i class="fas fa-circle-exclamation text-amber-500"></i> {{ str_replace('Alert:', '', $medicine->informasi_general) }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/20 px-3 py-1.5 rounded-lg border border-blue-100 dark:border-blue-900/30">
                                    <i class="fas fa-info-circle text-blue-500"></i> {{ str_replace('Alert:', '', $medicine->informasi_general) }}
                                </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
