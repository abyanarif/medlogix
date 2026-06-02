@extends('layouts.app')

@section('title', 'Data Stock & Reminder - MedLogix')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-black text-gray-950 dark:text-white flex items-center gap-2.5">
            <i class="fas fa-bell text-amber-500 animate-swing"></i> Data Stock &amp; Reminder
        </h1>
        <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">Pantau sisa stok fisik, nomor registrasi batch obat, tanggal kadaluarsa, beserta peringatan restock dinamis.</p>
    </div>
    
    <!-- Quick filter count badge list -->
    <div class="flex items-center gap-2">
        <span class="bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-xs font-bold px-3 py-1.5 rounded-xl">
            Aman: {{ $medicines->where('status', 'Aman')->count() }}
        </span>
        <span class="bg-amber-50 dark:bg-amber-950/20 border border-amber-100 dark:border-amber-900/30 text-amber-700 dark:text-amber-400 text-xs font-bold px-3 py-1.5 rounded-xl">
            Restock: {{ $medicines->where('status', 'Restock Segera')->count() }}
        </span>
        <span class="bg-rose-50 dark:bg-rose-950/20 border border-rose-100 dark:border-rose-900/30 text-rose-700 dark:text-rose-400 text-xs font-bold px-3 py-1.5 rounded-xl">
            Kadaluarsa: {{ $medicines->where('status', 'Kadaluarsa')->count() }}
        </span>
    </div>
</div>

<!-- Stock and Alert Table Container -->
<div x-data="{ search: '' }" class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden mb-8">
    <!-- Interactive Client-side Search and Reminder Settings Alert Info -->
    <div class="p-6 md:p-8 border-b border-gray-100 dark:border-gray-700 flex flex-col md:flex-row justify-between items-center gap-4">
        <!-- Search bar -->
        <div class="relative w-full md:max-w-xs">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                <i class="fas fa-search"></i>
            </div>
            <input 
                type="text" 
                x-model="search" 
                class="block w-full pl-9 pr-3 py-2 border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition"
                placeholder="Cari obat atau batch..."
            >
        </div>

        <!-- Info Parameter reminder -->
        <div class="flex items-center gap-2 text-xs font-bold text-gray-500 dark:text-gray-400">
            <i class="fas fa-sliders text-blue-500"></i>
            <span>Threshold Aktif: </span>
            <span class="bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 px-2 py-1 rounded-md">Stok &le; {{ $userNotification->batas_minimal_stok ?? 10 }}</span>
            <span>&amp;</span>
            <span class="bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 px-2 py-1 rounded-md">H-{{ $userNotification->waktu_restock_hari ?? 7 }} Exp</span>
        </div>
    </div>

    <!-- Data Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 dark:bg-gray-900/40 text-gray-400 dark:text-gray-500 text-xs font-bold uppercase tracking-wider border-b border-gray-100 dark:border-gray-700">
                    <th class="py-4 px-6">Nama Obat &amp; Brand</th>
                    <th class="py-4 px-6">Tanggal Masuk</th>
                    <th class="py-4 px-6 text-center">Nomor Batch</th>
                    <th class="py-4 px-6 text-center">Jumlah Stock</th>
                    <th class="py-4 px-6 text-center">Exp Date</th>
                    <th class="py-4 px-6 text-center">Status</th>
                    <th class="py-4 px-6 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700 text-sm">
                @foreach ($medicines as $medicine)
                    <tr 
                        x-show="search === '' || '{{ strtolower($medicine->nama_obat) }}'.includes(search.toLowerCase()) || '{{ strtolower($medicine->brand) }}'.includes(search.toLowerCase()) || '{{ strtolower($medicine->no_batch) }}'.includes(search.toLowerCase())"
                        class="hover:bg-gray-50/40 dark:hover:bg-gray-900/10 transition"
                    >
                        <!-- Medicine Name & Brand -->
                        <td class="py-4.5 px-6">
                            <div class="font-bold text-gray-900 dark:text-white">{{ $medicine->nama_obat }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $medicine->brand }}</div>
                        </td>

                        <!-- Tanggal Masuk -->
                        <td class="py-4.5 px-6 text-gray-600 dark:text-gray-300 font-medium">
                            {{ $medicine->tanggal_masuk->format('d M Y') }}
                        </td>

                        <!-- No. Batch -->
                        <td class="py-4.5 px-6 text-center font-mono font-bold text-gray-600 dark:text-gray-300">
                            {{ $medicine->no_batch }}
                        </td>

                        <!-- Jumlah Stock -->
                        <td class="py-4.5 px-6 text-center">
                            @if ($medicine->stok <= ($userNotification->batas_minimal_stok ?? 10))
                                <span class="font-black text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/20 px-2.5 py-1.5 rounded-lg text-xs border border-rose-100 dark:border-rose-900/30">
                                    {{ $medicine->stok }} <span class="text-[10px] text-rose-400 dark:text-rose-500 font-bold">Low!</span>
                                </span>
                            @else
                                <span class="font-bold text-gray-900 dark:text-white bg-gray-100 dark:bg-gray-700 px-2.5 py-1.5 rounded-lg text-xs">
                                    {{ $medicine->stok }} pcs
                                </span>
                            @endif
                        </td>

                        <!-- Exp Date -->
                        <td class="py-4.5 px-6 text-center">
                            @if ($medicine->remaining_days <= 0)
                                <div class="font-bold text-rose-600 dark:text-rose-400">{{ $medicine->exp_date->format('d M Y') }}</div>
                                <div class="text-[10px] text-rose-500 dark:text-rose-500 font-bold mt-0.5">Sudah Kadaluarsa!</div>
                            @elseif ($medicine->remaining_days <= ($userNotification->waktu_restock_hari ?? 7))
                                <div class="font-bold text-amber-600 dark:text-amber-400">{{ $medicine->exp_date->format('d M Y') }}</div>
                                <div class="text-[10px] text-amber-500 dark:text-amber-500 font-bold mt-0.5">Exp H-{{ $medicine->remaining_days }}!</div>
                            @else
                                <div class="font-medium text-gray-900 dark:text-white">{{ $medicine->exp_date->format('d M Y') }}</div>
                                <div class="text-[10px] text-emerald-500 font-bold mt-0.5">{{ $medicine->remaining_days }} hari lagi</div>
                            @endif
                        </td>

                        <!-- Calculated Status Badge -->
                        <td class="py-4.5 px-6 text-center">
                            @if ($medicine->status === 'Kadaluarsa')
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-rose-700 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/20 px-3 py-1.5 rounded-xl border border-rose-100 dark:border-rose-900/30 text-nowrap">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-ping"></span> Kadaluarsa
                                </span>
                            @elseif ($medicine->status === 'Restock Segera')
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/20 px-3 py-1.5 rounded-xl border border-amber-100 dark:border-amber-900/30 text-nowrap">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Restock Segera
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/20 px-3 py-1.5 rounded-xl border border-emerald-100 dark:border-emerald-900/30 text-nowrap">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aman
                                </span>
                            @endif
                        </td>

                        <!-- Action buttons -->
                        <td class="py-4.5 px-6 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('medicines.edit', $medicine->id) }}" class="inline-flex items-center justify-center p-2 rounded-lg bg-blue-50 dark:bg-blue-900/30 hover:bg-blue-100 dark:hover:bg-blue-900/50 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-900/20 transition" title="Edit Data Obat">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('medicines.dispense', $medicine->id) }}" class="inline-flex items-center justify-center p-2 rounded-lg bg-rose-50 dark:bg-rose-900/30 hover:bg-rose-100 dark:hover:bg-rose-900/50 text-rose-600 dark:text-rose-400 border border-rose-100 dark:border-rose-900/20 transition" title="Catat Pengeluaran (Dispense)">
                                    <i class="fas fa-minus-circle"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Explanatory Status Calculation Guide -->
<div class="bg-blue-50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-800/30 rounded-3xl p-6 md:p-8 flex items-start gap-4">
    <div class="text-3xl text-blue-600 dark:text-blue-400 mt-1">
        <i class="fas fa-info-circle"></i>
    </div>
    <div>
        <h4 class="font-extrabold text-blue-950 dark:text-blue-300 text-sm">Bagaimana Status Dinamis Dihitung?</h4>
        <p class="text-xs text-blue-800/80 dark:text-blue-400/80 mt-1 leading-relaxed">
            Status keaktifan obat dievaluasi secara dinamis oleh sistem MedLogix dengan parameter berikut:
        </p>
        <ul class="list-disc list-inside text-xs text-blue-800/80 dark:text-blue-400/80 mt-2 space-y-1 pl-1">
            <li><strong class="text-blue-950 dark:text-blue-300">Kadaluarsa:</strong> Jika Tanggal Kadaluarsa (Exp Date) bernilai hari ini atau sudah terlampaui.</li>
            <li><strong class="text-blue-950 dark:text-blue-300">Restock Segera:</strong> Jika fitur Alert Notifikasi aktif, dan jumlah sisa fisik stok berada pada atau di bawah batas minimal, <span class="underline">atau</span> jika waktu kadaluarsa tersisa kurang dari atau sama dengan H-x parameter restock.</li>
            <li><strong class="text-blue-950 dark:text-blue-300">Aman:</strong> Jika sisa stok berada di atas batas minimal dan jangka waktu kadaluarsa masih panjang melampaui parameter peringatan.</li>
        </ul>
    </div>
</div>
@endsection
