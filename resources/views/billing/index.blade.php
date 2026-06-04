@extends('layouts.app')

@section('title', 'Pembayaran & Langganan - MedLogix')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Header Card -->
    <div class="bg-gradient-to-r from-teal-900 to-teal-800 rounded-3xl p-6 md:p-8 text-white shadow-md mb-8 relative overflow-hidden">
        <div class="absolute right-0 top-0 translate-x-8 -translate-y-8 opacity-10 text-9xl">
            <i class="fas fa-credit-card"></i>
        </div>
        <div class="relative z-10">
            <span class="bg-teal-700/50 text-teal-100 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider border border-teal-600/30">
                MedLogix Premium
            </span>
            <h1 class="text-3xl font-black mt-2">Aktivasi Langganan B2B SaaS</h1>
            <p class="text-teal-100/90 text-sm mt-1">
                Lakukan transfer pembayaran untuk mengaktifkan seluruh fitur inventory apotek premium Anda.
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Bank Information Card -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <h2 class="text-lg font-black text-slate-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-university text-teal-850"></i>
                    Informasi Pembayaran
                </h2>
                <p class="text-xs text-slate-600 mb-6">
                    Silakan transfer biaya langganan bulanan ke rekening resmi MedLogix berikut:
                </p>

                <!-- Bank Details -->
                <div class="space-y-4">
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] uppercase font-bold text-slate-400 block font-sans">Nama Bank</span>
                            <span class="text-sm font-bold text-slate-900">{{ $settings['bank_name'] ?? 'N/A' }}</span>
                        </div>
                        <i class="fas fa-money-bill-transfer text-slate-300 text-xl"></i>
                    </div>

                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] uppercase font-bold text-slate-400 block font-sans">Nomor Rekening</span>
                            <span class="text-base font-mono font-bold text-slate-900 select-all">{{ $settings['account_number'] ?? 'N/A' }}</span>
                        </div>
                        <button onclick="navigator.clipboard.writeText('{{ $settings['account_number'] ?? '' }}'); alert('No. Rekening disalin!');" class="text-teal-700 hover:text-teal-900 text-xs font-bold bg-teal-50 px-2.5 py-1.5 rounded-lg transition" title="Copy Account Number">
                            <i class="fas fa-copy"></i> Salin
                        </button>
                    </div>

                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] uppercase font-bold text-slate-400 block font-sans">Atas Nama</span>
                            <span class="text-sm font-bold text-slate-900">{{ $settings['account_name'] ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] uppercase font-bold text-slate-400 block font-sans">Nominal Biaya</span>
                            <span class="text-base font-bold text-slate-900">Rp {{ number_format((int)($settings['monthly_fee'] ?? 0), 0, ',', '.') }} <span class="text-xs text-slate-500 font-medium">/ bulan</span></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 text-[10px] text-teal-800 flex items-start gap-2 bg-teal-50 p-3 rounded-xl border border-teal-200">
                <i class="fas fa-info-circle text-teal-600 mt-0.5"></i>
                <span>Harap simpan bukti pembayaran berupa gambar (JPG/PNG) dan pastikan data nominal terlihat dengan jelas.</span>
            </div>
        </div>

        <!-- Payment Action & Status Card -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <h2 class="text-lg font-black text-slate-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-file-invoice-dollar text-teal-850"></i>
                    Status Pembayaran
                </h2>

                <!-- Status Badge Banner -->
                <div class="mb-6">
                    @if ($user->payment_status === 'paid')
                        <div class="bg-emerald-50 border border-emerald-250 text-emerald-850 p-4 rounded-2xl flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600 text-lg">
                                <i class="fas fa-circle-check"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold">Langganan Aktif</h4>
                                <p class="text-xs text-emerald-600/80 mt-0.5">
                                    Aktif s.d {{ $user->subscription_ends_at ? $user->subscription_ends_at->format('d M Y') : 'Selamanya' }}
                                </p>
                            </div>
                        </div>
                    @elseif ($user->payment_status === 'pending' && $user->payment_receipt)
                        <div class="bg-amber-50 border border-amber-250 text-amber-850 p-4 rounded-2xl flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600 text-lg animate-pulse">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold">Menunggu Validasi</h4>
                                <p class="text-xs text-amber-600/80 mt-0.5">
                                    Admin sedang memproses bukti transfer Anda.
                                </p>
                            </div>
                        </div>
                    @elseif ($user->payment_status === 'rejected')
                        <div class="bg-red-50 border border-red-250 text-red-850 p-4 rounded-2xl flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center text-red-650 text-lg">
                                <i class="fas fa-circle-xmark"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold">Pembayaran Ditolak</h4>
                                <p class="text-xs text-red-600/80 mt-0.5">
                                    Bukti transfer tidak valid. Unggah ulang bukti Anda.
                                </p>
                            </div>
                        </div>
                    @else
                        <div class="bg-slate-50 border border-slate-200 text-slate-800 p-4 rounded-2xl flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-slate-200 flex items-center justify-center text-slate-650 text-lg">
                                <i class="fas fa-file-circle-question"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold">Belum Ada Pembayaran</h4>
                                <p class="text-xs text-slate-500 mt-0.5">Silakan upload bukti transfer Anda.</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Action Form or Receipt Show -->
                @if ($user->payment_status === 'pending' && $user->payment_receipt)
                    <div class="border-2 border-dashed border-slate-200 rounded-3xl p-4 flex flex-col items-center justify-center text-center">
                        <span class="text-xs font-semibold text-slate-500 mb-3 font-sans">
                            Bukti Transfer Terkirim:
                        </span>
                        <div class="relative group max-w-[200px] rounded-2xl overflow-hidden shadow-sm border border-slate-200">
                            <img src="{{ asset($user->payment_receipt) }}" alt="Bukti Pembayaran" class="h-40 w-auto object-cover filter brightness-75 hover:brightness-100 transition">
                            <a href="{{ asset($user->payment_receipt) }}" target="_blank" class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 text-white font-bold text-xs transition">
                                <i class="fas fa-expand mr-1"></i> Perbesar
                            </a>
                        </div>
                    </div>
                @else
                    <!-- Form Upload -->
                    <form action="{{ route('billing.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 font-sans">Upload Bukti Transfer</label>
                            
                            <!-- Custom File Dropzone Feel -->
                            <div class="relative border-2 border-dashed border-slate-300 hover:border-teal-500 rounded-2xl p-6 text-center cursor-pointer transition" onclick="document.getElementById('file-upload-input').click()">
                                <input id="file-upload-input" type="file" name="payment_receipt" accept="image/*" class="hidden" onchange="previewFile(event)">
                                <div class="space-y-2">
                                    <i class="fas fa-cloud-upload-alt text-teal-650 text-3xl"></i>
                                    <div class="text-xs text-slate-650 font-sans">
                                        <span class="font-bold text-teal-700">Klik untuk upload</span> atau drag file gambar
                                    </div>
                                    <p class="text-[10px] text-slate-400">PNG, JPG, JPEG maks. 2MB</p>
                                </div>
                            </div>
                            
                            <!-- Preview Thumbnail Area -->
                            <div id="preview-container" class="hidden mt-4 border border-slate-200 p-3 rounded-2xl flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <img id="image-preview" src="#" alt="Preview" class="w-12 h-12 rounded-lg object-cover shadow-sm border border-slate-200">
                                    <div class="text-left">
                                        <span id="file-name" class="text-xs font-bold text-slate-800 block truncate max-w-[150px]">file-name.jpg</span>
                                        <span class="text-[10px] text-emerald-500 font-semibold"><i class="fas fa-check-circle"></i> Terpilih</span>
                                    </div>
                                </div>
                                <button type="button" onclick="removeSelectedFile(event)" class="text-red-650 hover:text-red-700 bg-red-50 p-2 rounded-lg text-xs font-bold transition">
                                    <i class="fas fa-trash-alt"></i> Hapus
                                </button>
                            </div>

                            @error('payment_receipt')
                                <span class="text-xs text-red-500 font-semibold mt-1 block">
                                    <i class="fas fa-triangle-exclamation mr-1"></i> {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <button type="submit" class="w-full bg-teal-900 hover:bg-teal-800 text-white font-bold py-3 rounded-xl transition shadow-md hover:shadow-lg text-sm flex items-center justify-center gap-2">
                            <i class="fas fa-paper-plane"></i> Kirim Bukti Pembayaran
                        </button>
                    </form>
                @endif
            </div>

            <!-- Footer Details -->
            @if ($user->payment_status === 'paid')
                <div class="mt-6">
                    <a href="{{ route('dashboard') }}" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold py-3 rounded-xl transition text-center text-sm block">
                        <i class="fas fa-circle-left mr-1"></i> Kembali ke Dashboard
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    function previewFile(event) {
        const input = event.target;
        const previewContainer = document.getElementById('preview-container');
        const previewImage = document.getElementById('image-preview');
        const fileName = document.getElementById('file-name');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                previewImage.src = e.target.result;
                fileName.textContent = input.files[0].name;
                previewContainer.classList.remove('hidden');
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeSelectedFile(event) {
        event.stopPropagation();
        const input = document.getElementById('file-upload-input');
        const previewContainer = document.getElementById('preview-container');
        const previewImage = document.getElementById('image-preview');
        
        input.value = "";
        previewImage.src = "#";
        previewContainer.classList.add('hidden');
    }
</script>
@endsection
