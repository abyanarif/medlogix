@extends('layouts.app')

@section('title', 'Admin Dashboard - MedLogix')

@section('content')
<!-- Header Section -->
<div class="bg-gradient-to-r from-teal-900 to-teal-800 rounded-3xl p-6 md:p-8 text-white shadow-md mb-8 relative overflow-hidden">
    <div class="absolute right-0 top-0 translate-x-8 -translate-y-8 opacity-10 text-9xl">
        <i class="fas fa-user-shield"></i>
    </div>
    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <span class="bg-teal-700/50 text-teal-100 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider border border-teal-600/30">
                <i class="fas fa-shield-halved mr-1"></i> Panel Superadmin
            </span>
            <h1 class="text-3xl font-black mt-2">Dashboard Pengelola MedLogix</h1>
            <p class="text-teal-100/90 text-sm mt-1">
                Kelola pendaftaran apoteker B2B, verifikasi pembayaran manual, dan pantau status subscription platform.
            </p>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-xs font-bold text-teal-100 flex items-center gap-1.5 bg-teal-950/40 border border-teal-800/40 px-3.5 py-2 rounded-xl">
                <span class="w-2.5 h-2.5 rounded-full bg-teal-400 animate-pulse"></span> Mode: Live Monitoring
            </span>
        </div>
    </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
    <!-- Stat 1: Total Revenue -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-800 flex items-center justify-center text-2xl">
            <i class="fas fa-money-bill text-emerald-700"></i>
        </div>
        <div>
            <p class="text-slate-600 text-xs font-bold uppercase tracking-wider">Total Pendapatan</p>
            <h3 class="text-2xl font-black text-slate-900 mt-0.5">
                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
            </h3>
        </div>
    </div>

    <!-- Stat 2: Active Pharmacies -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-teal-50 border border-teal-100 text-teal-800 flex items-center justify-center text-2xl">
            <i class="fas fa-clinic-medical text-teal-700"></i>
        </div>
        <div>
            <p class="text-slate-600 text-xs font-bold uppercase tracking-wider">Apotek Aktif</p>
            <h3 class="text-2xl font-black text-slate-900 mt-0.5">
                {{ $activePharmacies }} <span class="text-xs text-slate-450 font-medium">Mitra</span>
            </h3>
        </div>
    </div>

    <!-- Stat 3: Pending Validation -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-100 text-amber-800 flex items-center justify-center text-2xl">
            <i class="fas fa-clock text-amber-700"></i>
        </div>
        <div>
            <p class="text-slate-600 text-xs font-bold uppercase tracking-wider">Menunggu Validasi</p>
            <h3 class="text-2xl font-black text-slate-900 mt-0.5">
                {{ $pendingApprovals }} <span class="text-xs text-slate-450 font-medium">Antrean</span>
            </h3>
        </div>
    </div>
</div>

<!-- Pharmacists Table Card -->
<div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden mb-8">
    <div class="p-6 md:p-8 border-b border-slate-200 flex flex-col sm:flex-row justify-between sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-black text-slate-900 flex items-center gap-2">
                <i class="fas fa-users-gear text-teal-900"></i> 
                Daftar Mitra Apoteker
            </h2>
            <p class="text-slate-600 text-xs mt-1">Review status berlangganan dan bukti pembayaran transfer dari apoteker terdaftar.</p>
        </div>
    </div>

    <!-- Search and Filter Form -->
    <div class="px-6 py-4 bg-slate-50/30 border-b border-slate-200">
        <form method="GET" action="{{ route('admin.dashboard') }}">
            <div class="flex flex-col md:flex-row md:items-center gap-4">
                <!-- Search Input -->
                <div class="flex-grow">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="Cari nama atau email..." 
                        class="w-full bg-white border border-slate-300 focus:ring-teal-500 focus:border-teal-500 text-slate-900 rounded-xl px-4 py-2.5 text-sm shadow-sm focus:outline-none transition duration-150"
                    >
                </div>

                <!-- Status Filter -->
                <div class="w-full md:w-48">
                    <select 
                        name="status" 
                        class="w-full bg-white border border-slate-300 focus:ring-teal-500 focus:border-teal-500 text-slate-900 rounded-xl px-4 py-2.5 text-sm shadow-sm focus:outline-none transition duration-150"
                    >
                        <option value="" {{ request('status') === null || request('status') === '' ? 'selected' : '' }}>Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending Review</option>
                        <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <button 
                        type="submit" 
                        class="w-full md:w-auto bg-teal-900 hover:bg-teal-800 text-white font-bold px-6 py-2.5 rounded-xl shadow-sm hover:shadow transition duration-150 text-sm flex items-center justify-center gap-2"
                    >
                        <i class="fas fa-search text-xs"></i> Cari
                    </button>
                    <a 
                        href="{{ route('admin.dashboard') }}" 
                        class="w-full md:w-auto text-center text-slate-500 hover:text-slate-900 font-semibold px-4 py-2.5 rounded-xl hover:bg-slate-100/85 transition duration-150 text-sm block border border-transparent hover:border-slate-200"
                    >
                        Reset Filter
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Responsive Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-600 text-xs font-bold uppercase tracking-wider border-b border-slate-200">
                    <th class="py-4 px-6">Nama Apoteker</th>
                    <th class="py-4 px-6">Email &amp; Telepon</th>
                    <th class="py-4 px-6 text-center">Status Pembayaran</th>
                    <th class="py-4 px-6 text-center">Bukti Transfer</th>
                    <th class="py-4 px-6 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @forelse ($users as $user)
                    <tr class="hover:bg-slate-50/50 transition">
                        <!-- Name & SIPA -->
                        <td class="py-4.5 px-6">
                            <div class="font-bold text-slate-900">{{ $user->name }}</div>
                            <div class="text-xs text-slate-650 mt-0.5 flex items-center gap-1">
                                <i class="fas fa-id-card text-[10px] text-teal-700"></i> SIPA: {{ $user->sipa }}
                            </div>
                            <!-- Subscription Plan Details -->
                            <div class="mt-2 text-[11px] text-slate-500 flex flex-col gap-0.5">
                                <div>Plan Aktif: <span class="font-bold text-teal-850 capitalize">{{ $user->subscription_plan ?? 'Trial' }}</span></div>
                                <div>Slot Obat: <span class="font-bold text-slate-850">{{ $user->max_slots ?? 50 }} Slot</span></div>
                                @if($user->yearly_bonus_claimed)
                                    <div class="text-[10px] text-emerald-600 font-semibold flex items-center gap-0.5">
                                        <i class="fas fa-gift"></i> Bonus Tahunan Diklaim
                                    </div>
                                @endif
                            </div>
                        </td>

                        <!-- Email & Phone -->
                        <td class="py-4.5 px-6">
                            <div class="text-slate-900 font-semibold">{{ $user->email }}</div>
                            <div class="text-xs text-slate-600 mt-0.5 flex items-center gap-1">
                                <i class="fas fa-phone text-[10px] text-slate-400"></i> {{ $user->phone }}
                            </div>
                        </td>

                        <!-- Payment Status -->
                        <td class="py-4.5 px-6 text-center">
                            @php
                                $statusStyle = match($user->payment_status) {
                                    'paid' => 'text-emerald-800 bg-emerald-50 border border-emerald-200',
                                    'pending' => 'text-amber-800 bg-amber-50 border border-amber-200',
                                    'rejected' => 'text-red-800 bg-red-50 border border-red-200',
                                    default => 'text-slate-700 bg-slate-50 border border-slate-250',
                                };
                                $statusLabel = match($user->payment_status) {
                                    'paid' => 'Paid (Aktif)',
                                    'pending' => 'Pending Review',
                                    'rejected' => 'Rejected',
                                    default => 'Unknown',
                                };
                            @endphp
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full border {{ $statusStyle }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $user->payment_status === 'paid' ? 'bg-emerald-500' : ($user->payment_status === 'pending' ? 'bg-amber-500 animate-pulse' : 'bg-red-500') }}"></span>
                                {{ $statusLabel }}
                            </span>

                            @if($user->payment_status === 'pending' && $user->pending_plan)
                                <div class="mt-2 text-[10px] bg-slate-50 border border-slate-200 text-slate-700 rounded-lg p-1.5 text-left inline-block max-w-[160px]">
                                    <div class="font-bold text-teal-800 mb-0.5"><i class="fas fa-shopping-cart text-[9px]"></i> Rincian Beli:</div>
                                    <div class="space-y-0.5 text-slate-650">
                                        <div>Paket: <span class="font-bold capitalize">{{ $user->pending_plan === 'monthly' ? 'Bulanan' : 'Tahunan' }}</span></div>
                                        @if($user->pending_addon_qty > 0)
                                            <div>Ekstra: <span class="font-bold">+{{ $user->pending_addon_qty }} Slot</span></div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </td>

                        <!-- Payment Receipt -->
                        <td class="py-4.5 px-6 text-center">
                            @if ($user->payment_receipt)
                                <a href="{{ asset($user->payment_receipt) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-bold text-teal-700 bg-teal-50 hover:bg-teal-100 border border-teal-200 px-3 py-1.5 rounded-xl transition">
                                    <i class="fas fa-eye"></i> Lihat Bukti
                                </a>
                            @else
                                <span class="text-xs font-semibold text-slate-400">
                                    Belum Upload
                                </span>
                            @endif
                        </td>

                        <!-- Action Column -->
                        <td class="py-4.5 px-6 text-center">
                            @if ($user->payment_status === 'pending')
                                <div class="inline-flex items-center gap-2">
                                    <!-- Approve Action -->
                                    <form action="{{ route('admin.users.approve', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui pembayaran ini?');">
                                        @csrf
                                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-3 py-1.5 rounded-xl text-xs flex items-center gap-1 transition shadow-sm">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                    </form>

                                    <!-- Reject Action -->
                                    <form action="{{ route('admin.users.reject', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menolak pembayaran ini?');">
                                        @csrf
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold px-3 py-1.5 rounded-xl text-xs flex items-center gap-1 transition shadow-sm">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    </form>
                                </div>
                            @elseif ($user->payment_status === 'paid')
                                <span class="text-xs text-slate-600 font-semibold italic flex items-center justify-center gap-1">
                                    <i class="fas fa-calendar-check text-emerald-500"></i> Exp: {{ $user->subscription_ends_at ? $user->subscription_ends_at->format('d M Y') : '-' }}
                                </span>
                            @else
                                <span class="text-xs text-slate-600 font-semibold italic">
                                    Tidak ada aksi
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-slate-600 font-semibold">
                            <i class="fas fa-users-slash text-3xl mb-2 block text-slate-300"></i>
                            Belum ada apoteker terdaftar
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Links -->
    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
        {{ $users->links() }}
    </div>
</div>

<!-- B2B Settings Card -->
<div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden mb-8">
    <div class="p-6 md:p-8 border-b border-slate-200">
        <h2 class="text-xl font-black text-slate-900 flex items-center gap-2">
            <i class="fas fa-sliders text-teal-900"></i> 
            Pengaturan Pembayaran B2B
        </h2>
        <p class="text-slate-650 text-xs mt-1">Konfigurasi rekening tujuan pembayaran transfer manual dan nominal biaya langganan bulanan.</p>
    </div>

    <div class="p-6 md:p-8">
        <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Nama Bank -->
                <div>
                    <label for="bank_name" class="block text-xs font-bold uppercase tracking-wider text-slate-650 mb-1.5">Nama Bank</label>
                    <input 
                        id="bank_name" 
                        type="text" 
                        name="bank_name" 
                        value="{{ old('bank_name', $settings['bank_name'] ?? '') }}"
                        required
                        class="block w-full px-3.5 py-2.5 border border-slate-300 bg-white text-slate-900 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition text-sm"
                        placeholder="Contoh: Bank Central Asia (BCA)"
                    >
                </div>

                <!-- Nomor Rekening -->
                <div>
                    <label for="account_number" class="block text-xs font-bold uppercase tracking-wider text-slate-650 mb-1.5">Nomor Rekening</label>
                    <input 
                        id="account_number" 
                        type="text" 
                        name="account_number" 
                        value="{{ old('account_number', $settings['account_number'] ?? '') }}"
                        required
                        class="block w-full px-3.5 py-2.5 border border-slate-300 bg-white text-slate-900 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition text-sm font-mono"
                        placeholder="Contoh: 12345678"
                    >
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Atas Nama Rekening -->
                <div>
                    <label for="account_name" class="block text-xs font-bold uppercase tracking-wider text-slate-650 mb-1.5">Atas Nama Rekening</label>
                    <input 
                        id="account_name" 
                        type="text" 
                        name="account_name" 
                        value="{{ old('account_name', $settings['account_name'] ?? '') }}"
                        required
                        class="block w-full px-3.5 py-2.5 border border-slate-300 bg-white text-slate-900 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition text-sm"
                        placeholder="Contoh: MedLogix"
                    >
                </div>

                <!-- Nominal Biaya Langganan -->
                <div>
                    <label for="monthly_fee" class="block text-xs font-bold uppercase tracking-wider text-slate-650 mb-1.5">Nominal Biaya Bulanan Lama (Rupiah)</label>
                    <input 
                        id="monthly_fee" 
                        type="number" 
                        name="monthly_fee" 
                        value="{{ old('monthly_fee', $settings['monthly_fee'] ?? '') }}"
                        required
                        min="0"
                        class="block w-full px-3.5 py-2.5 border border-slate-300 bg-white text-slate-900 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition text-sm font-semibold text-slate-500"
                        placeholder="Contoh: 50000"
                    >
                </div>
            </div>

            <!-- Dynamic Pricing Fields -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <!-- price_monthly -->
                <div>
                    <label for="price_monthly" class="block text-xs font-bold uppercase tracking-wider text-slate-650 mb-1.5">Biaya Paket Bulanan (Rupiah)</label>
                    <input 
                        id="price_monthly" 
                        type="number" 
                        name="price_monthly" 
                        value="{{ old('price_monthly', $settings['price_monthly'] ?? '30000') }}"
                        required
                        min="0"
                        class="block w-full px-3.5 py-2.5 border border-slate-300 bg-white text-slate-900 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition text-sm font-semibold"
                        placeholder="Contoh: 30000"
                    >
                </div>

                <!-- price_yearly -->
                <div>
                    <label for="price_yearly" class="block text-xs font-bold uppercase tracking-wider text-slate-650 mb-1.5">Biaya Paket Tahunan (Rupiah)</label>
                    <input 
                        id="price_yearly" 
                        type="number" 
                        name="price_yearly" 
                        value="{{ old('price_yearly', $settings['price_yearly'] ?? '300000') }}"
                        required
                        min="0"
                        class="block w-full px-3.5 py-2.5 border border-slate-300 bg-white text-slate-900 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition text-sm font-semibold"
                        placeholder="Contoh: 300000"
                    >
                </div>

                <!-- price_addon_slot -->
                <div>
                    <label for="price_addon_slot" class="block text-xs font-bold uppercase tracking-wider text-slate-650 mb-1.5">Biaya Ekstra 10 Slot (Rupiah)</label>
                    <input 
                        id="price_addon_slot" 
                        type="number" 
                        name="price_addon_slot" 
                        value="{{ old('price_addon_slot', $settings['price_addon_slot'] ?? '30000') }}"
                        required
                        min="0"
                        class="block w-full px-3.5 py-2.5 border border-slate-300 bg-white text-slate-900 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition text-sm font-semibold"
                        placeholder="Contoh: 30000"
                    >
                </div>
            </div>

            <button 
                type="submit"
                class="bg-teal-900 hover:bg-teal-800 text-white font-bold py-3 px-6 rounded-xl shadow-md hover:shadow-lg transition duration-200 text-sm flex items-center justify-center gap-1.5"
            >
                <i class="fas fa-save"></i> Simpan Pengaturan
            </button>
        </form>
    </div>
</div>
@endsection
