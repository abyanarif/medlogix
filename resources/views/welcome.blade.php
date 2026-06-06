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
            <div class="flex flex-wrap items-center gap-2">
                <span class="bg-teal-700/50 text-teal-100 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider border border-teal-600/30">
                    <i class="fas fa-check-circle mr-1"></i> Apoteker Terverifikasi
                </span>
                
                @if (auth()->user()->subscription_plan === 'trial')
                    @php
                        $daysLeft = auth()->user()->subscription_ends_at ? max(0, now()->startOfDay()->diffInDays(Carbon\Carbon::parse(auth()->user()->subscription_ends_at)->startOfDay(), false)) : 0;
                    @endphp
                    <span class="bg-amber-400 text-slate-950 text-xs font-black px-3 py-1 rounded-full uppercase tracking-wider flex items-center gap-1 border border-amber-300/40">
                        <i class="fas fa-hourglass-half"></i> Trial Berakhir dalam {{ $daysLeft }} Hari
                    </span>
                @elseif (auth()->user()->subscription_plan === 'monthly')
                    <span class="bg-emerald-500 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider flex items-center gap-1 border border-emerald-400/40">
                        <i class="fas fa-crown text-amber-300"></i> Paket: Bulanan
                    </span>
                @elseif (auth()->user()->subscription_plan === 'yearly')
                    <span class="bg-emerald-500 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider flex items-center gap-1 border border-emerald-400/40">
                        <i class="fas fa-crown text-amber-300"></i> Paket: Tahunan
                    </span>
                @endif
            </div>
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

<!-- Slot Capacity Card -->
<div class="bg-white rounded-3xl p-6 md:p-8 border border-slate-200 shadow-sm mb-8">
    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-teal-50 border border-teal-150 text-teal-900 flex items-center justify-center text-3xl">
                <i class="fas fa-cubes-stacked text-teal-850"></i>
            </div>
            <div>
                <h3 class="text-lg font-black text-slate-900">Kapasitas Slot Obat</h3>
                <p class="text-slate-550 text-xs mt-0.5">Pantau dan kelola batas penyimpanan jenis obat apotek Anda.</p>
            </div>
        </div>
        <div class="flex flex-col sm:items-end gap-2">
            <span class="text-xl font-black text-slate-900">
                {{ auth()->user()->medicines()->count() }} <span class="text-slate-400 font-semibold text-sm">/ {{ auth()->user()->max_slots ?? 50 }} Slot Terpakai</span>
            </span>
            <a href="{{ route('billing.index') }}" class="bg-teal-700 hover:bg-teal-800 text-white font-bold px-4 py-2 rounded-xl transition shadow-sm hover:shadow text-xs flex items-center justify-center gap-1.5">
                <i class="fas fa-credit-card"></i> Upgrade &amp; Tambah Slot
            </a>
        </div>
    </div>
    
    <!-- Progress Bar -->
    @php
        $count = auth()->user()->medicines()->count();
        $max = auth()->user()->max_slots ?? 50;
        $percentage = min(100, round(($count / $max) * 100));
        $barColor = $percentage >= 90 ? 'bg-red-500' : ($percentage >= 75 ? 'bg-amber-500' : 'bg-teal-600');
    @endphp
    <div class="mt-6">
        <div class="flex justify-between text-[11px] font-bold text-slate-650 mb-1.5 uppercase tracking-wider">
            <span>Status Penyimpanan</span>
            <span class="font-mono">{{ $percentage }}%</span>
        </div>
        <div class="w-full bg-slate-100 rounded-full h-3 border border-slate-200 overflow-hidden p-0.5">
            <div class="{{ $barColor }} h-2 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
        </div>
        @if ($percentage >= 90)
            <p class="text-[10px] text-red-600 font-bold mt-2 flex items-center gap-1">
                <i class="fas fa-triangle-exclamation animate-bounce"></i> Slot penyimpanan hampir penuh. Silakan lakukan upgrade atau beli slot ekstra!
            </p>
        @endif
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
