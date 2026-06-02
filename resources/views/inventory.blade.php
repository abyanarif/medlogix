@extends('layouts.app')

@section('title', 'Kelola Inventory - MedLogix')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-black text-gray-950 dark:text-white flex items-center gap-2.5">
        <i class="fas fa-boxes text-blue-600"></i> Kelola Inventory &amp; Notifikasi
    </h1>
    <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">Registrasikan data obat masuk baru dan konfigurasi parameter sistem reminder stok apotek Anda.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Form 1: Input Data Obat (2/3 Width) -->
    <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm p-6 md:p-8">
        <h2 class="text-xl font-bold text-gray-950 dark:text-white mb-6 flex items-center gap-2">
            <i class="fas fa-plus-circle text-emerald-500"></i> Form Input Data Obat Baru
        </h2>

        <form method="POST" action="{{ route('inventory.store') }}" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Nama Obat -->
                <div>
                    <label for="nama_obat" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">Nama Obat</label>
                    <input 
                        id="nama_obat" 
                        type="text" 
                        name="nama_obat" 
                        required
                        class="block w-full px-3.5 py-2.5 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-sm"
                        placeholder="Contoh: Paracetamol, Amoxicillin"
                    >
                </div>

                <!-- Brand / Pabrikan -->
                <div>
                    <label for="brand" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">Brand / Merek Dagang</label>
                    <input 
                        id="brand" 
                        type="text" 
                        name="brand" 
                        required
                        class="block w-full px-3.5 py-2.5 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-sm"
                        placeholder="Contoh: Sanmol, Panadol"
                    >
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <!-- Tanggal Masuk -->
                <div>
                    <label for="tanggal_masuk" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">Tanggal Masuk</label>
                    <input 
                        id="tanggal_masuk" 
                        type="date" 
                        name="tanggal_masuk" 
                        value="{{ date('Y-m-d') }}"
                        required
                        class="block w-full px-3.5 py-2.5 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-sm"
                    >
                </div>

                <!-- No. Batch -->
                <div>
                    <label for="no_batch" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">Nomor Batch</label>
                    <input 
                        id="no_batch" 
                        type="text" 
                        name="no_batch" 
                        required
                        class="block w-full px-3.5 py-2.5 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-sm"
                        placeholder="Contoh: PCM240501A"
                    >
                </div>

                <!-- Jumlah Stock -->
                <div>
                    <label for="stok" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">Jumlah Stok (Butir/Pcs)</label>
                    <input 
                        id="stok" 
                        type="number" 
                        name="stok" 
                        required
                        min="0"
                        class="block w-full px-3.5 py-2.5 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-sm"
                        placeholder="100"
                    >
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <!-- Exp Date -->
                <div>
                    <label for="exp_date" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">Tanggal Kadaluarsa (Exp Date)</label>
                    <input 
                        id="exp_date" 
                        type="date" 
                        name="exp_date" 
                        required
                        class="block w-full px-3.5 py-2.5 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-sm"
                    >
                </div>

                <!-- Harga Obat -->
                <div>
                    <label for="harga" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">Harga Obat Satuan (Rupiah)</label>
                    <input 
                        id="harga" 
                        type="number" 
                        name="harga" 
                        required
                        min="0"
                        class="block w-full px-3.5 py-2.5 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-sm"
                        placeholder="Rp"
                    >
                </div>

                <!-- Alert Level -->
                <div>
                    <label for="alert_level" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">Alert Level (Prioritas)</label>
                    <select 
                        id="alert_level" 
                        name="alert_level" 
                        required
                        class="block w-full px-3.5 py-2.5 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-sm"
                    >
                        <option value="info" selected>Info (Blue)</option>
                        <option value="warning">Warning (Yellow)</option>
                        <option value="danger">Danger (Red)</option>
                    </select>
                </div>
            </div>

            <!-- Informasi General -->
            <div>
                <label for="informasi_general" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">Informasi General / Petunjuk Penggunaan / Peringatan</label>
                <textarea 
                    id="informasi_general" 
                    name="informasi_general" 
                    rows="3"
                    required
                    class="block w-full px-3.5 py-2.5 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-sm"
                    placeholder="Contoh: Hati-hati pada pasien penyakit hati, Konsumsi setelah makan, Harus dihabiskan."
                ></textarea>
            </div>

            <button 
                type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transition duration-200 text-sm flex items-center justify-center gap-1.5"
            >
                <i class="fas fa-save"></i> Simpan Data Obat Masuk
            </button>
        </form>
    </div>

    <!-- Form 2: Pengaturan Notifikasi (1/3 Width) -->
    <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm p-6 md:p-8 flex flex-col justify-between h-fit">
        <div>
            <h2 class="text-xl font-bold text-gray-950 dark:text-white mb-6 flex items-center gap-2">
                <i class="fas fa-bell-slash text-amber-500"></i> Pengaturan Notifikasi
            </h2>

            <form method="POST" action="{{ route('notifications.update') }}" class="space-y-6">
                @csrf

                <!-- Batas Minimal Stok -->
                <div>
                    <label for="batas_minimal_stok" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">Batas Minimal Stok (Alert)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <input 
                            id="batas_minimal_stok" 
                            type="number" 
                            name="batas_minimal_stok" 
                            value="{{ $userNotification->batas_minimal_stok ?? 10 }}"
                            required
                            min="0"
                            class="block w-full pl-10 pr-3.5 py-2.5 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-sm"
                        >
                    </div>
                    <p class="text-gray-400 text-[10px] mt-1">Sistem akan menandai obat dengan status "Restock Segera" jika stok &le; angka batas ini.</p>
                </div>

                <!-- Waktu Restock Hari (Reminder H-X) -->
                <div>
                    <label for="waktu_restock_hari" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">Waktu Restock (H-x Kadaluarsa)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <input 
                            id="waktu_restock_hari" 
                            type="number" 
                            name="waktu_restock_hari" 
                            value="{{ $userNotification->waktu_restock_hari ?? 7 }}"
                            required
                            min="0"
                            class="block w-full pl-10 pr-3.5 py-2.5 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-sm"
                        >
                    </div>
                    <p class="text-gray-400 text-[10px] mt-1">Sistem akan menandai obat dengan status "Restock Segera" jika exp_date mendekati $N$ hari dari sekarang.</p>
                </div>

                <!-- Email/Phone ON/OFF Toggle -->
                <div class="bg-gray-50 dark:bg-gray-900/40 border border-gray-100 dark:border-gray-700/80 rounded-2xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="block text-xs font-bold text-gray-800 dark:text-gray-200">Alert Email &amp; WhatsApp</span>
                            <span class="block text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">Kirim pengingat restock eksternal secara aktif.</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer select-none">
                            <input 
                                type="checkbox" 
                                name="is_active" 
                                class="sr-only peer" 
                                {{ ($userNotification->is_active ?? true) ? 'checked' : '' }}
                            >
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>

                <button 
                    type="submit"
                    class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-2.5 px-4 rounded-xl shadow-md hover:shadow-lg transition duration-200 text-sm flex items-center justify-center gap-1.5"
                >
                    <i class="fas fa-check"></i> Simpan Konfigurasi
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
