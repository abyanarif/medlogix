@extends('layouts.app')

@section('title', 'Catat Pengeluaran Obat - MedLogix')

@section('content')
<div class="mb-8 flex items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-black text-gray-950 dark:text-white flex items-center gap-2.5">
            <i class="fas fa-minus-circle text-rose-500"></i> Catat Pengeluaran Obat
        </h1>
        <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">Kurangi stok fisik obat dan catat volume keluaran distribusi secara real-time.</p>
    </div>
    <a href="{{ route('stock-reminder') }}" class="bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold px-4 py-2 rounded-xl transition text-xs flex items-center gap-1.5">
        <i class="fas fa-arrow-left"></i> Kembali ke Stock
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    <!-- Left Column: Medicine Read-only Details (1/3 Width) -->
    <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm p-6 md:p-8 flex flex-col justify-between text-center relative overflow-hidden">
        <div class="absolute right-0 top-0 translate-x-4 -translate-y-4 opacity-5 text-8xl text-rose-500">
            <i class="fas fa-box-open"></i>
        </div>
        
        <div>
            <span class="bg-rose-50 dark:bg-rose-950/20 text-rose-700 dark:text-rose-400 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                Dispensing Target
            </span>
            <h3 class="text-2xl font-black text-gray-900 dark:text-white mt-4">{{ $medicine->nama_obat }}</h3>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1 font-semibold flex items-center justify-center gap-1.5">
                <span><i class="fas fa-tags text-[10px] text-blue-500"></i> {{ $medicine->brand }}</span>
                <span>&bull;</span>
                <span><i class="fas fa-barcode text-[10px] text-purple-500"></i> Batch: <code class="font-mono">{{ $medicine->no_batch }}</code></span>
            </p>
        </div>

        <div class="my-8 border-y border-gray-100 dark:border-gray-700 py-6">
            <p class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-widest font-black">Stok Fisik Tersedia</p>
            <div class="text-5xl font-black text-rose-600 dark:text-rose-400 mt-2 font-mono">
                {{ $medicine->stok }}
            </div>
            <p class="text-[10px] text-gray-400 mt-1">butir / pcs</p>
        </div>

        <div class="text-[11px] text-gray-500 dark:text-gray-400 flex items-center justify-center gap-1.5 bg-gray-50 dark:bg-gray-900/40 p-3 rounded-2xl border border-gray-100 dark:border-gray-700/80">
            <i class="fas fa-exclamation-triangle text-amber-500 text-sm"></i>
            <span class="text-left leading-normal font-semibold">Volume keluar tidak diperbolehkan melebihi sisa stok fisik apotek.</span>
        </div>
    </div>

    <!-- Right Column: Dispensing Transaction Form (2/3 Width) -->
    <div class="md:col-span-2 bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm p-6 md:p-8">
        <h2 class="text-xl font-bold text-gray-950 dark:text-white mb-6 flex items-center gap-2">
            <i class="fas fa-clipboard-list text-rose-500"></i> Catat Transaksi Dispensing
        </h2>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-rose-50 dark:bg-rose-950/20 border-l-4 border-rose-500 text-rose-800 dark:text-rose-400 rounded-xl">
                <div class="flex">
                    <div class="py-1"><i class="fas fa-exclamation-triangle mr-3 text-lg text-rose-500"></i></div>
                    <div>
                        <h5 class="font-bold text-sm mb-1">Terjadi kesalahan pada input:</h5>
                        <ul class="list-disc list-inside text-xs space-y-0.5 font-semibold">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('medicines.dispense.submit', $medicine->id) }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Jumlah Keluar -->
                <div>
                    <label for="jumlah_keluar" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">Jumlah Pengeluaran (Dispense Qty)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <i class="fas fa-shopping-basket"></i>
                        </div>
                        <input 
                            id="jumlah_keluar" 
                            type="number" 
                            name="jumlah_keluar" 
                            required
                            min="1"
                            max="{{ $medicine->stok }}"
                            class="block w-full pl-10 pr-3.5 py-2.5 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition text-sm font-bold text-rose-600 dark:text-rose-400"
                            placeholder="Contoh: 10"
                        >
                    </div>
                    <p class="text-[10px] text-gray-400 mt-1">Masukkan angka pengeluaran (Maksimal: {{ $medicine->stok }} pcs).</p>
                </div>

                <!-- Tanggal Keluar -->
                <div>
                    <label for="tanggal_keluar" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">Tanggal Distribusi / Keluar</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <input 
                            id="tanggal_keluar" 
                            type="date" 
                            name="tanggal_keluar" 
                            value="{{ date('Y-m-d') }}"
                            required
                            class="block w-full pl-10 pr-3.5 py-2.5 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition text-sm"
                        >
                    </div>
                </div>
            </div>

            <div class="flex gap-4 pt-3">
                <button 
                    type="submit"
                    class="flex-grow bg-rose-600 hover:bg-rose-700 text-white font-bold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transition duration-200 text-sm flex items-center justify-center gap-1.5"
                >
                    <i class="fas fa-box-open"></i> Kurangi Stok &amp; Catat Transaksi
                </button>
                <a 
                    href="{{ route('stock-reminder') }}"
                    class="bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-bold py-3 px-6 rounded-xl transition text-sm flex items-center justify-center"
                >
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
