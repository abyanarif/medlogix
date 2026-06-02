@extends('layouts.app')

@section('title', 'Log Transaksi - MedLogix')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-black text-gray-950 dark:text-white flex items-center gap-2.5">
        <i class="fas fa-history text-blue-600"></i> Riwayat Mutasi &amp; Log Transaksi
    </h1>
    <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">Audit trail komprehensif mencatat riwayat masuk (inflow) dan keluar (outflow) obat-obatan di apotek Anda.</p>
</div>

<!-- Tab System using Alpine.js -->
<div x-data="{ activeTab: 'inflow' }" class="space-y-6">
    <!-- Tab Selectors -->
    <div class="flex border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-2 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-750 gap-2 max-w-md">
        <!-- Tab 1: Inflow -->
        <button 
            @click="activeTab = 'inflow'"
            :class="activeTab === 'inflow' 
                ? 'bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 font-extrabold shadow-sm' 
                : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-900 font-semibold'"
            class="flex-grow py-3 px-4 rounded-xl text-xs transition duration-200 flex items-center justify-center gap-2"
        >
            <i class="fas fa-arrow-down-long text-emerald-500"></i> Riwayat Obat Masuk (Inflow)
        </button>

        <!-- Tab 2: Outflow -->
        <button 
            @click="activeTab = 'outflow'"
            :class="activeTab === 'outflow' 
                ? 'bg-rose-50 dark:bg-rose-950/20 text-rose-700 dark:text-rose-400 font-extrabold shadow-sm' 
                : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-900 font-semibold'"
            class="flex-grow py-3 px-4 rounded-xl text-xs transition duration-200 flex items-center justify-center gap-2"
        >
            <i class="fas fa-arrow-up-long text-rose-500"></i> Riwayat Obat Keluar (Outflow)
        </button>
    </div>

    <!-- Tab 1 Content: Riwayat Obat Masuk (Inflow Table) -->
    <div x-show="activeTab === 'inflow'" x-transition class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="p-6 md:p-8 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-bold text-gray-950 dark:text-white flex items-center gap-2">
                    <i class="fas fa-arrow-circle-down text-emerald-500"></i> Riwayat Obat Masuk (Inflow)
                </h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Daftar pencatatan obat baru yang masuk ke database inventory apotek.</p>
            </div>
            <span class="bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-xs font-bold px-3 py-1.5 rounded-xl">
                Total Mutasi: {{ $inflows->count() }} Record
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 dark:bg-gray-900/40 text-gray-400 dark:text-gray-500 text-xs font-bold uppercase tracking-wider border-b border-gray-100 dark:border-gray-700">
                        <th class="py-4 px-6">Tanggal Masuk</th>
                        <th class="py-4 px-6">Nama Obat &amp; Brand</th>
                        <th class="py-4 px-6 text-center">Nomor Batch</th>
                        <th class="py-4 px-6 text-center">Jumlah Stok Awal</th>
                        <th class="py-4 px-6 text-center">Harga Beli</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700 text-sm">
                    @forelse ($inflows as $inflow)
                        <tr class="hover:bg-gray-50/40 dark:hover:bg-gray-900/10 transition">
                            <!-- Tanggal Masuk -->
                            <td class="py-4.5 px-6 font-semibold text-gray-600 dark:text-gray-300">
                                {{ $inflow->tanggal_masuk ? $inflow->tanggal_masuk->format('d M Y') : '-' }}
                            </td>

                            <!-- Nama Obat & Brand -->
                            <td class="py-4.5 px-6">
                                <div class="font-bold text-gray-900 dark:text-white">{{ $inflow->nama_obat }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $inflow->brand }}</div>
                            </td>

                            <!-- Nomor Batch -->
                            <td class="py-4.5 px-6 text-center font-mono font-bold text-gray-700 dark:text-gray-300">
                                {{ $inflow->no_batch }}
                            </td>

                            <!-- Jumlah Stok Awal -->
                            <td class="py-4.5 px-6 text-center">
                                <span class="font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/20 px-2.5 py-1.5 rounded-lg text-xs border border-emerald-100 dark:border-emerald-900/30">
                                    +{{ $inflow->stok }} pcs
                                </span>
                            </td>

                            <!-- Harga Beli -->
                            <td class="py-4.5 px-6 text-center">
                                <span class="font-mono font-bold text-gray-800 dark:text-gray-200">
                                    Rp {{ number_format($inflow->harga, 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-gray-400 dark:text-gray-500">
                                <i class="fas fa-box-open text-4xl mb-3 block"></i>
                                Belum ada riwayat obat masuk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tab 2 Content: Riwayat Obat Keluar (Outflow Table) -->
    <div x-show="activeTab === 'outflow'" x-transition class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="p-6 md:p-8 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-bold text-gray-950 dark:text-white flex items-center gap-2">
                    <i class="fas fa-arrow-circle-up text-rose-500"></i> Riwayat Obat Keluar (Outflow)
                </h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Daftar transaksi pengeluaran (dispensing) obat keluar dari apotek.</p>
            </div>
            <span class="bg-rose-50 dark:bg-rose-950/20 border border-rose-100 dark:border-rose-900/30 text-rose-700 dark:text-rose-400 text-xs font-bold px-3 py-1.5 rounded-xl">
                Total Mutasi: {{ $outflows->count() }} Record
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 dark:bg-gray-900/40 text-gray-400 dark:text-gray-500 text-xs font-bold uppercase tracking-wider border-b border-gray-100 dark:border-gray-700">
                        <th class="py-4 px-6">Tanggal Keluar</th>
                        <th class="py-4 px-6">Nama Obat &amp; Brand</th>
                        <th class="py-4 px-6 text-center">Nomor Batch</th>
                        <th class="py-4 px-6 text-center">Jumlah Keluar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700 text-sm">
                    @forelse ($outflows as $outflow)
                        <tr class="hover:bg-gray-50/40 dark:hover:bg-gray-900/10 transition">
                            <!-- Tanggal Keluar -->
                            <td class="py-4.5 px-6 font-semibold text-gray-600 dark:text-gray-300">
                                {{ $outflow->tanggal_keluar ? $outflow->tanggal_keluar->format('d M Y') : '-' }}
                            </td>

                            <!-- Nama Obat & Brand -->
                            <td class="py-4.5 px-6">
                                @if($outflow->medicine)
                                    <div class="font-bold text-gray-900 dark:text-white">{{ $outflow->medicine->nama_obat }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $outflow->medicine->brand }}</div>
                                @else
                                    <div class="font-bold text-gray-400 dark:text-gray-500">[Obat Terhapus]</div>
                                @endif
                            </td>

                            <!-- Nomor Batch -->
                            <td class="py-4.5 px-6 text-center font-mono font-bold text-gray-700 dark:text-gray-300">
                                {{ $outflow->medicine ? $outflow->medicine->no_batch : '-' }}
                            </td>

                            <!-- Jumlah Keluar -->
                            <td class="py-4.5 px-6 text-center">
                                <span class="font-bold text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/20 px-2.5 py-1.5 rounded-lg text-xs border border-rose-100 dark:border-rose-900/30">
                                    -{{ $outflow->jumlah_keluar }} pcs
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center text-gray-400 dark:text-gray-500">
                                <i class="fas fa-box-open text-4xl mb-3 block"></i>
                                Belum ada riwayat obat keluar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
