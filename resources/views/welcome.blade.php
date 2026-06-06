@extends('layouts.app')

@section('title', 'Dashboard Apoteker - MedLogix')

@section('content')
<!-- Header Pharmacist Details Card -->
<div class="bg-gradient-to-r from-teal-900 to-teal-800 rounded-3xl p-6 md:p-8 text-white shadow-md mb-8 relative overflow-hidden">
    <div class="absolute right-0 top-0 translate-x-8 -translate-y-8 opacity-10 text-9xl">
        <i class="fas fa-prescription-bottle-medical"></i>
    </div>
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
        <div>
            <span class="bg-teal-700/50 text-teal-100 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider border border-teal-600/30">
                <i class="fas fa-check-circle mr-1"></i> Apoteker Terverifikasi
            </span>
            <h1 class="text-3xl font-black mt-2">Apotek Apoteker {{ Auth::user()->name }}</h1>
            <p class="text-teal-100/90 text-sm mt-1 flex flex-wrap items-center gap-4">
                <span><i class="fas fa-id-card mr-1 text-teal-300"></i> SIPA: <strong class="text-white">{{ Auth::user()->sipa }}</strong></span>
                <span class="hidden md:inline">&bull;</span>
                <span><i class="fas fa-map-marker-alt mr-1 text-teal-300"></i> {{ Auth::user()->apotek_address }}</span>
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('inventory') }}" class="bg-white hover:bg-teal-50 text-teal-900 font-bold px-5 py-2.5 rounded-xl transition shadow-md hover:shadow-lg text-sm flex items-center gap-1.5">
                <i class="fas fa-plus-circle text-teal-700"></i> Input Obat Baru
            </a>
            <a href="{{ route('stock-reminder') }}" class="bg-teal-750/50 hover:bg-teal-700/70 text-white font-bold px-5 py-2.5 rounded-xl transition text-sm flex items-center gap-1.5 border border-teal-600/30">
                <i class="fas fa-bell text-teal-300"></i> Alerts ({{ $alertCount }})
            </a>
        </div>
    </div>
</div>

<!-- Stats Dashboard Overview -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
    <!-- Stat 1: Total Medicines -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-teal-50 border border-teal-100 text-teal-800 flex items-center justify-center text-2xl">
            <i class="fas fa-box-open text-teal-700"></i>
        </div>
        <div>
            <p class="text-slate-600 text-xs font-bold uppercase tracking-wider">Total Jenis Obat</p>
            <h3 class="text-2xl font-black text-slate-900 mt-0.5">{{ $medicines->total() }}</h3>
        </div>
    </div>

    <!-- Stat 2: Total Sales/Outflow -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-800 flex items-center justify-center text-2xl">
            <i class="fas fa-shopping-basket text-emerald-700"></i>
        </div>
        <div>
            <p class="text-slate-600 text-xs font-bold uppercase tracking-wider">Simulasi Obat Keluar</p>
            <h3 class="text-2xl font-black text-slate-900 mt-0.5">{{ $totalDispensed }} <span class="text-xs text-slate-600 font-medium">butir</span></h3>
        </div>
    </div>

    <!-- Stat 3: Restock Warning Settings -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-100 text-amber-800 flex items-center justify-center text-2xl">
            <i class="fas fa-triangle-exclamation text-amber-700"></i>
        </div>
        <div>
            <p class="text-slate-600 text-xs font-bold uppercase tracking-wider">Restock Alert Threshold</p>
            <h3 class="text-2xl font-black text-slate-900 mt-0.5">
                Stok &le; {{ $userNotification->batas_minimal_stok ?? 10 }}
            </h3>
        </div>
    </div>
</div>

<!-- Dashboard Apoteker: Summary Drug Sales & Outflow List -->
<div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden mb-8">
    <div class="p-6 md:p-8 border-b border-slate-200 flex flex-col sm:flex-row justify-between sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-black text-slate-900 flex items-center gap-2">
                <i class="fas fa-clipboard-list text-teal-800"></i> 
                Aliran & Informasi Obat
            </h2>
            <p class="text-slate-600 text-xs mt-1">Daftar obat-obatan beserta simulasi volume keluar/pembelian dan info penanganan.</p>
        </div>
        <span class="text-xs font-bold text-slate-400 flex items-center gap-1.5">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span> Update: Realtime
        </span>
    </div>

    <!-- Responsive Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-600 text-xs font-bold uppercase tracking-wider border-b border-slate-200">
                    <th class="py-4 px-6">Nama Obat &amp; Brand</th>
                    <th class="py-4 px-6 text-center">Harga Satuan</th>
                    <th class="py-4 px-6">Volume Keluar / Pembelian</th>
                    <th class="py-4 px-6">Informasi General / Alert</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @foreach ($medicines as $medicine)
                    <tr class="hover:bg-slate-50/50 transition">
                        <!-- Medicine Name & Brand -->
                        <td class="py-4.5 px-6">
                            <div class="font-bold text-slate-900">{{ $medicine->nama_obat }}</div>
                            <div class="text-xs text-slate-600 mt-0.5 flex items-center gap-1">
                                <i class="fas fa-tags text-[10px] text-teal-700/80"></i> {{ $medicine->brand }}
                            </div>
                        </td>

                        <!-- Unit Price -->
                        <td class="py-4.5 px-6 text-center">
                            <span class="font-mono font-bold text-slate-900 bg-slate-100 border border-slate-200 px-2.5 py-1.5 rounded-lg text-xs">
                                Rp {{ number_format($medicine->harga, 0, ',', '.') }}
                            </span>
                        </td>

                        <!-- Simulated Drug Sales/Outflow Volume -->
                        <td class="py-4.5 px-6">
                            <div class="flex items-center gap-3 max-w-[200px]">
                                <div class="flex-grow bg-slate-100 rounded-full h-2">
                                    @php
                                        // Calculate percentage for progress bars relative to a base of 200 units max
                                        $percent = min(100, round(($medicine->obat_keluar / 200) * 100));
                                    @endphp
                                    <div class="bg-emerald-500 h-2 rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
                                </div>
                                <span class="font-bold text-slate-700 text-xs text-nowrap">
                                    {{ $medicine->obat_keluar }} <span class="text-[10px] text-slate-400 font-medium">pcs</span>
                                </span>
                            </div>
                        </td>

                        <!-- General Information Warning alerts -->
                        <td class="py-4.5 px-6">
                            @php
                                $badgeStyle = match($medicine->alert_level ?? 'info') {
                                    'danger' => 'text-red-700 bg-red-50 border border-red-100',
                                    'warning' => 'text-amber-700 bg-amber-50 border border-amber-100',
                                    'info' => 'text-teal-800 bg-teal-50 border border-teal-200',
                                    default => 'text-slate-700 bg-slate-50 border border-slate-200',
                                };
                                $icon = match($medicine->alert_level ?? 'info') {
                                    'danger' => 'fa-triangle-exclamation text-red-500',
                                    'warning' => 'fa-circle-exclamation text-amber-500',
                                    'info' => 'fa-info-circle text-teal-600',
                                    default => 'fa-circle-question text-slate-500',
                                };
                            @endphp
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg border {{ $badgeStyle }}">
                                <i class="fas {{ $icon }}"></i> {{ str_replace('Alert:', '', $medicine->informasi_general) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- Pagination Links -->
    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
        {{ $medicines->links() }}
    </div>
</div>
@endsection
