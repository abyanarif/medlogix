@extends('layouts.app')

@section('title', 'Edit Data Obat - MedLogix')

@section('content')
<div class="mb-8 flex items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-black text-slate-900 flex items-center gap-2.5">
            <i class="fas fa-edit text-teal-800"></i> Edit Data Obat
        </h1>
        <p class="text-slate-650 text-xs mt-1">Perbarui parameter data obat, tingkat peringatan, stok fisik, atau harga satuan obat.</p>
    </div>
    <a href="{{ route('stock-reminder') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-850 font-bold px-4 py-2 rounded-xl transition text-xs flex items-center gap-1.5 border border-slate-200">
        <i class="fas fa-arrow-left"></i> Kembali ke Stock
    </a>
</div>

<div class="max-w-3xl bg-white rounded-3xl border border-slate-200 shadow-sm p-6 md:p-8 mx-auto">
    <h2 class="text-xl font-bold text-slate-900 mb-6 flex items-center gap-2">
        <i class="fas fa-prescription-bottle-medical text-teal-700"></i> Form Edit Parameter Obat
    </h2>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-800 rounded-xl">
            <div class="flex">
                <div class="py-1"><i class="fas fa-exclamation-triangle mr-3 text-lg text-rose-500"></i></div>
                <div>
                    <h5 class="font-bold text-sm mb-1">Periksa kembali input Anda:</h5>
                    <ul class="list-disc list-inside text-xs space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('medicines.update', $medicine->id) }}" class="space-y-5">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <!-- Nama Obat -->
            <div>
                <label for="nama_obat" class="block text-xs font-bold uppercase tracking-wider text-slate-650 mb-1.5">Nama Obat</label>
                <input 
                    id="nama_obat" 
                    type="text" 
                    name="nama_obat" 
                    value="{{ old('nama_obat', $medicine->nama_obat) }}"
                    required
                    class="block w-full px-3.5 py-2.5 border border-slate-300 bg-white text-slate-900 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition text-sm"
                    placeholder="Contoh: Paracetamol"
                >
            </div>

            <!-- Brand / Merek Dagang -->
            <div>
                <label for="brand" class="block text-xs font-bold uppercase tracking-wider text-slate-650 mb-1.5">Brand / Merek Dagang</label>
                <input 
                    id="brand" 
                    type="text" 
                    name="brand" 
                    value="{{ old('brand', $medicine->brand) }}"
                    required
                    class="block w-full px-3.5 py-2.5 border border-slate-300 bg-white text-slate-900 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition text-sm"
                    placeholder="Contoh: Sanmol"
                >
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <!-- Tanggal Masuk -->
            <div>
                <label for="tanggal_masuk" class="block text-xs font-bold uppercase tracking-wider text-slate-650 mb-1.5">Tanggal Masuk</label>
                <input 
                    id="tanggal_masuk" 
                    type="date" 
                    name="tanggal_masuk" 
                    value="{{ old('tanggal_masuk', $medicine->tanggal_masuk ? $medicine->tanggal_masuk->format('Y-m-d') : '') }}"
                    required
                    class="block w-full px-3.5 py-2.5 border border-slate-300 bg-white text-slate-900 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition text-sm"
                >
            </div>

            <!-- Nomor Batch -->
            <div>
                <label for="no_batch" class="block text-xs font-bold uppercase tracking-wider text-slate-650 mb-1.5">Nomor Batch</label>
                <input 
                    id="no_batch" 
                    type="text" 
                    name="no_batch" 
                    value="{{ old('no_batch', $medicine->no_batch) }}"
                    required
                    class="block w-full px-3.5 py-2.5 border border-slate-300 bg-white text-slate-900 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition text-sm font-mono"
                    placeholder="Contoh: PCM240501A"
                >
            </div>

            <!-- Jumlah Stock -->
            <div>
                <label for="stok" class="block text-xs font-bold uppercase tracking-wider text-slate-650 mb-1.5">Jumlah Stok</label>
                <input 
                    id="stok" 
                    type="number" 
                    name="stok" 
                    value="{{ old('stok', $medicine->stok) }}"
                    required
                    min="0"
                    class="block w-full px-3.5 py-2.5 border border-slate-300 bg-white text-slate-900 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition text-sm font-semibold"
                >
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <!-- Exp Date -->
            <div>
                <label for="exp_date" class="block text-xs font-bold uppercase tracking-wider text-slate-650 mb-1.5">Tanggal Kadaluarsa (Exp Date)</label>
                <input 
                    id="exp_date" 
                    type="date" 
                    name="exp_date" 
                    value="{{ old('exp_date', $medicine->exp_date ? $medicine->exp_date->format('Y-m-d') : '') }}"
                    required
                    class="block w-full px-3.5 py-2.5 border border-slate-300 bg-white text-slate-900 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition text-sm"
                >
            </div>

            <!-- Harga Obat -->
            <div>
                <label for="harga" class="block text-xs font-bold uppercase tracking-wider text-slate-650 mb-1.5">Harga Satuan (Rupiah)</label>
                <input 
                    id="harga" 
                    type="number" 
                    name="harga" 
                    value="{{ old('harga', $medicine->harga) }}"
                    required
                    min="0"
                    class="block w-full px-3.5 py-2.5 border border-slate-300 bg-white text-slate-900 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition text-sm font-semibold"
                >
            </div>

            <!-- Alert Level -->
            <div>
                <label for="alert_level" class="block text-xs font-bold uppercase tracking-wider text-slate-650 mb-1.5">Alert Level (Prioritas)</label>
                <select 
                    id="alert_level" 
                    name="alert_level" 
                    required
                    class="block w-full px-3.5 py-2.5 border border-slate-300 bg-white text-slate-900 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition text-sm font-semibold"
                >
                    <option value="info" {{ old('alert_level', $medicine->alert_level) === 'info' ? 'selected' : '' }}>Info (Teal)</option>
                    <option value="warning" {{ old('alert_level', $medicine->alert_level) === 'warning' ? 'selected' : '' }}>Warning (Yellow)</option>
                    <option value="danger" {{ old('alert_level', $medicine->alert_level) === 'danger' ? 'selected' : '' }}>Danger (Red)</option>
                </select>
            </div>
        </div>

        <!-- Informasi General -->
        <div>
            <label for="informasi_general" class="block text-xs font-bold uppercase tracking-wider text-slate-650 mb-1.5">Informasi General / Petunjuk Penggunaan / Peringatan</label>
            <textarea 
                id="informasi_general" 
                name="informasi_general" 
                rows="3"
                required
                class="block w-full px-3.5 py-2.5 border border-slate-300 bg-white text-slate-900 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition text-sm"
                placeholder="Tuliskan petunjuk penanganan atau alert medis obat..."
            >{{ old('informasi_general', str_replace('Alert: ', '', $medicine->informasi_general)) }}</textarea>
        </div>

        <div class="flex gap-4 pt-3">
            <button 
                type="submit"
                class="flex-grow bg-teal-900 hover:bg-teal-800 text-white font-bold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transition duration-200 text-sm flex items-center justify-center gap-1.5"
            >
                <i class="fas fa-check-double"></i> Simpan Perubahan
            </button>
            <a 
                href="{{ route('stock-reminder') }}"
                class="bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold py-3 px-6 rounded-xl transition text-sm flex items-center justify-center border border-slate-200"
            >
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
