@extends('layouts.app')

@section('title', 'Log Transaksi - MedLogix')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-black text-slate-900 flex items-center gap-2.5">
        <i class="fas fa-history text-teal-800"></i> Riwayat Mutasi &amp; Log Transaksi
    </h1>
    <p class="text-slate-600 text-xs mt-1">Audit trail komprehensif mencatat riwayat masuk (inflow) dan keluar (outflow) obat-obatan di apotek Anda.</p>
</div>

<!-- Tab System using Alpine.js -->
<div x-data="{ activeTab: 'inflow' }" class="space-y-6">
    <!-- Tab Selectors -->
    <div class="flex border border-slate-200 bg-white p-2 rounded-2xl shadow-sm gap-2 max-w-md">
        <!-- Tab 1: Inflow -->
        <button 
            @click="activeTab = 'inflow'"
            :class="activeTab === 'inflow' 
                ? 'bg-emerald-50 border border-emerald-200 text-emerald-800 font-extrabold shadow-sm' 
                : 'text-slate-600 hover:bg-slate-50 font-semibold'"
            class="flex-grow py-3 px-4 rounded-xl text-xs transition duration-200 flex items-center justify-center gap-2"
        >
            <i class="fas fa-arrow-down-long text-emerald-600"></i> Riwayat Obat Masuk (Inflow)
        </button>

        <!-- Tab 2: Outflow -->
        <button 
            @click="activeTab = 'outflow'"
            :class="activeTab === 'outflow' 
                ? 'bg-rose-50 border border-rose-200 text-rose-800 font-extrabold shadow-sm' 
                : 'text-slate-600 hover:bg-slate-50 font-semibold'"
            class="flex-grow py-3 px-4 rounded-xl text-xs transition duration-200 flex items-center justify-center gap-2"
        >
            <i class="fas fa-arrow-up-long text-rose-600"></i> Riwayat Obat Keluar (Outflow)
        </button>
    </div>

    <!-- Tab 1 Content: Riwayat Obat Masuk (Inflow Table) -->
    <div x-show="activeTab === 'inflow'" x-transition class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 md:p-8 border-b border-slate-200 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <i class="fas fa-arrow-circle-down text-emerald-600"></i> Riwayat Obat Masuk (Inflow)
                </h3>
                <p class="text-xs text-slate-600 mt-1">Daftar pencatatan obat baru yang masuk ke database inventory apotek.</p>
            </div>
            <span class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold px-3 py-1.5 rounded-xl">
                Total Mutasi: {{ $inflows->total() }} Record
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-600 text-xs font-bold uppercase tracking-wider border-b border-slate-200">
                        <th class="py-4 px-6">Tanggal Masuk</th>
                        <th class="py-4 px-6">Nama Obat &amp; Brand</th>
                        <th class="py-4 px-6 text-center">Nomor Batch</th>
                        <th class="py-4 px-6 text-center">Jumlah Stok Awal</th>
                        <th class="py-4 px-6 text-center">Harga Beli</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse ($inflows as $inflow)
                        <tr class="hover:bg-slate-50/50 transition">
                            <!-- Tanggal Masuk -->
                            <td class="py-4.5 px-6 font-semibold text-slate-800">
                                {{ $inflow->tanggal_masuk ? $inflow->tanggal_masuk->format('d M Y') : '-' }}
                            </td>

                            <!-- Nama Obat & Brand -->
                            <td class="py-4.5 px-6">
                                <div class="font-bold text-slate-900">{{ $inflow->nama_obat }}</div>
                                <div class="text-xs text-slate-600 mt-0.5">{{ $inflow->brand }}</div>
                            </td>

                            <!-- Nomor Batch -->
                            <td class="py-4.5 px-6 text-center font-mono font-bold text-slate-800">
                                {{ $inflow->no_batch }}
                            </td>

                            <!-- Jumlah Stok Awal -->
                            <td class="py-4.5 px-6 text-center">
                                <span class="font-bold text-emerald-800 bg-emerald-50 px-2.5 py-1.5 rounded-lg text-xs border border-emerald-200">
                                    +{{ $inflow->stok }} pcs
                                </span>
                            </td>

                            <!-- Harga Beli -->
                            <td class="py-4.5 px-6 text-center">
                                <span class="font-mono font-bold text-slate-900">
                                    Rp {{ number_format($inflow->harga, 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-500">
                                <i class="fas fa-box-open text-4xl mb-3 block text-slate-300"></i>
                                Belum ada riwayat obat masuk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Pagination Links for Inflows -->
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $inflows->appends(['activeTab' => 'inflow'])->links() }}
        </div>
    </div>

    <!-- Tab 2 Content: Riwayat Obat Keluar (Outflow Table) -->
    <div x-show="activeTab === 'outflow'" x-transition class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 md:p-8 border-b border-slate-200 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <i class="fas fa-arrow-circle-up text-rose-600"></i> Riwayat Obat Keluar (Outflow)
                </h3>
                <p class="text-xs text-slate-600 mt-1">Daftar transaksi pengeluaran (dispensing) obat keluar dari apotek.</p>
            </div>
            <span class="bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold px-3 py-1.5 rounded-xl">
                Total Mutasi: {{ $outflows->total() }} Record
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-600 text-xs font-bold uppercase tracking-wider border-b border-slate-200">
                        <th class="py-4 px-6">Tanggal Keluar</th>
                        <th class="py-4 px-6">Nama Obat &amp; Brand</th>
                        <th class="py-4 px-6 text-center">Nomor Batch</th>
                        <th class="py-4 px-6 text-center">Jumlah Keluar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse ($outflows as $outflow)
                        <tr class="hover:bg-slate-50/50 transition">
                            <!-- Tanggal Keluar -->
                            <td class="py-4.5 px-6 font-semibold text-slate-800">
                                {{ $outflow->tanggal_keluar ? $outflow->tanggal_keluar->format('d M Y') : '-' }}
                            </td>

                            <!-- Nama Obat & Brand -->
                            <td class="py-4.5 px-6">
                                @if($outflow->medicine)
                                    <div class="font-bold text-slate-900">{{ $outflow->medicine->nama_obat }}</div>
                                    <div class="text-xs text-slate-600 mt-0.5">{{ $outflow->medicine->brand }}</div>
                                @else
                                    <div class="font-bold text-slate-400">[Obat Terhapus]</div>
                                @endif
                            </td>

                            <!-- Nomor Batch -->
                            <td class="py-4.5 px-6 text-center font-mono font-bold text-slate-800">
                                {{ $outflow->medicine ? $outflow->medicine->no_batch : '-' }}
                            </td>

                            <!-- Jumlah Keluar -->
                            <td class="py-4.5 px-6 text-center">
                                <span class="font-bold text-rose-800 bg-rose-50 px-2.5 py-1.5 rounded-lg text-xs border border-rose-200">
                                    -{{ $outflow->jumlah_keluar }} pcs
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center text-slate-500">
                                <i class="fas fa-box-open text-4xl mb-3 block text-slate-300"></i>
                                Belum ada riwayat obat keluar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Pagination Links for Outflows -->
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $outflows->appends(['activeTab' => 'outflow'])->links() }}
        </div>
    </div>
</div>
@endsection
