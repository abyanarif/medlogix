@extends('layouts.app')

@section('title', 'Pembayaran & Langganan - MedLogix')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header Card -->
    <div class="bg-gradient-to-r from-teal-900 to-teal-800 rounded-3xl p-6 md:p-8 text-white shadow-md mb-8 relative overflow-hidden">
        <div class="absolute right-0 top-0 translate-x-8 -translate-y-8 opacity-10 text-9xl">
            <i class="fas fa-credit-card"></i>
        </div>
        <div class="relative z-10">
            <span class="bg-teal-700/50 text-teal-100 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider border border-teal-600/30">
                MedLogix Premium Pricing
            </span>
            <h1 class="text-3xl font-black mt-2">Pilih Paket Langganan B2B Anda</h1>
            <p class="text-teal-100/90 text-sm mt-1">
                Pilih paket terbaik untuk apotek Anda, tambahkan ekstra slot penyimpanan obat sesuai kebutuhan, dan aktifkan akses premium.
            </p>
        </div>
    </div>

    <!-- Main Content Form & Details -->
    <form action="{{ route('billing.upload') }}" method="POST" enctype="multipart/form-data" id="billing-form">
        @csrf

        <!-- Plan Selection Section -->
        <h2 class="text-lg font-black text-slate-900 mb-4 flex items-center gap-2">
            <i class="fas fa-cubes text-teal-850"></i>
            Pilih Paket Berlangganan
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Paket Bulanan Card -->
            <label class="relative block cursor-pointer group">
                <input type="radio" name="pending_plan" value="monthly" class="sr-only peer" checked onchange="updatePricing()">
                <div class="h-full bg-white rounded-3xl p-6 border-2 border-slate-200 peer-checked:border-teal-600 hover:border-teal-500 shadow-sm transition-all duration-200 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-slate-900 text-sm font-black uppercase tracking-wider">Paket Bulanan</span>
                            <span class="text-xs font-bold text-teal-700 bg-teal-50 px-2.5 py-1 rounded-full">Populer</span>
                        </div>
                        <div class="mb-4">
                            <span class="text-3xl font-black text-slate-900">Rp {{ number_format((int)($settings['price_monthly'] ?? 30000), 0, ',', '.') }}</span>
                            <span class="text-slate-550 text-xs font-medium">/ bulan</span>
                        </div>
                        <ul class="space-y-2 text-xs text-slate-650 mb-6">
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-teal-500"></span>
                                <strong>50 Slot Obat</strong> (Kapasitas bawaan)
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-teal-500"></span>
                                Akses penuh manajemen inventory obat
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-teal-500"></span>
                                Monitoring kadaluarsa obat real-time
                            </li>
                        </ul>
                    </div>
                    <div class="pt-4 border-t border-slate-100 flex justify-between items-center text-xs">
                        <span class="text-slate-500">Pilih Paket</span>
                        <div class="w-5 h-5 rounded-full border-2 border-slate-300 peer-checked:bg-teal-600 flex items-center justify-center text-white text-[10px] bg-white group-hover:border-teal-500" id="bullet-monthly">
                            <i class="fas fa-check opacity-0"></i>
                        </div>
                    </div>
                </div>
            </label>

            <!-- Paket Tahunan Card -->
            <label class="relative block cursor-pointer group">
                <input type="radio" name="pending_plan" value="yearly" class="sr-only peer" onchange="updatePricing()">
                <div class="h-full bg-white rounded-3xl p-6 border-2 border-slate-200 peer-checked:border-teal-600 hover:border-teal-500 shadow-sm transition-all duration-200 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-slate-900 text-sm font-black uppercase tracking-wider">Paket Tahunan</span>
                            <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full">Hemat Terbesar</span>
                        </div>
                        <div class="mb-4">
                            <span class="text-3xl font-black text-slate-900">Rp {{ number_format((int)($settings['price_yearly'] ?? 300000), 0, ',', '.') }}</span>
                            <span class="text-slate-550 text-xs font-medium">/ tahun</span>
                        </div>
                        <ul class="space-y-2 text-xs text-slate-650 mb-6">
                            <li class="flex items-center gap-2 text-teal-900 font-semibold bg-teal-50/50 p-2 rounded-xl border border-teal-100">
                                <i class="fas fa-gift text-teal-650 text-xs"></i>
                                <span>Bonus <strong>+50 Slot</strong> selamanya (Pembelian pertama)</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-teal-500"></span>
                                Total kapasitas awal: <strong>100 Slot Obat</strong>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-teal-500"></span>
                                Prioritas dukungan CS & Pembaruan
                            </li>
                        </ul>
                    </div>
                    <div class="pt-4 border-t border-slate-100 flex justify-between items-center text-xs">
                        <span class="text-slate-500">Pilih Paket</span>
                        <div class="w-5 h-5 rounded-full border-2 border-slate-300 peer-checked:bg-teal-600 flex items-center justify-center text-white text-[10px] bg-white group-hover:border-teal-500" id="bullet-yearly">
                            <i class="fas fa-check opacity-0"></i>
                        </div>
                    </div>
                </div>
            </label>
        </div>

        <!-- Addon Slots Section -->
        <div class="bg-white rounded-3xl p-6 md:p-8 border border-slate-200 shadow-sm mb-8">
            <h3 class="text-md font-black text-slate-900 mb-2 flex items-center gap-2">
                <i class="fas fa-cart-plus text-teal-850"></i>
                Beli Ekstra Slot Obat
            </h3>
            <p class="text-slate-600 text-xs mb-6">
                Ingin menyimpan lebih banyak jenis obat di inventory? Tambahkan kapasitas slot obat ekstra Anda dalam kelipatan 10 slot. 
                Hanya dikenakan <strong>Rp {{ number_format((int)($settings['price_addon_slot'] ?? 30000), 0, ',', '.') }}</strong> per 10 slot tambahan.
            </p>

            <div class="max-w-xs">
                <label for="pending_addon_qty" class="block text-xs font-bold uppercase tracking-wider text-slate-650 mb-1.5">Jumlah Tambahan Slot Obat</label>
                <div class="relative flex items-center">
                    <button type="button" onclick="adjustAddon(-10)" class="absolute left-2 bg-slate-100 hover:bg-slate-200 text-slate-700 w-8 h-8 rounded-lg font-bold transition flex items-center justify-center text-sm focus:outline-none">-</button>
                    <input 
                        id="pending_addon_qty" 
                        type="number" 
                        name="pending_addon_qty" 
                        value="0"
                        min="0"
                        step="10"
                        readonly
                        class="block w-full text-center px-10 py-2.5 border border-slate-300 bg-white text-slate-950 rounded-xl shadow-sm focus:outline-none transition text-sm font-bold font-mono"
                        onchange="updatePricing()"
                    >
                    <button type="button" onclick="adjustAddon(10)" class="absolute right-2 bg-slate-100 hover:bg-slate-200 text-slate-700 w-8 h-8 rounded-lg font-bold transition flex items-center justify-center text-sm focus:outline-none">+</button>
                </div>
                <p class="text-[10px] text-slate-500 mt-1.5"><i class="fas fa-circle-info text-teal-600"></i> Pembelian ekstra slot disetujui selamanya.</p>
            </div>
        </div>

        <!-- Billing Summary & Receipt Upload Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Payment and Bank Account Details -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <h3 class="text-md font-black text-slate-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-university text-teal-850"></i>
                        Informasi Pembayaran
                    </h3>
                    <p class="text-xs text-slate-650 mb-6">
                        Silakan transfer nominal total biaya tagihan di samping ke rekening resmi MedLogix berikut:
                    </p>

                    <!-- Bank Details -->
                    <div class="space-y-3.5">
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 flex items-center justify-between">
                            <div>
                                <span class="text-[9px] uppercase font-bold text-slate-400 block tracking-wider">Nama Bank</span>
                                <span class="text-sm font-bold text-slate-900">{{ $settings['bank_name'] ?? 'Bank Central Asia (BCA)' }}</span>
                            </div>
                            <i class="fas fa-money-bill-transfer text-slate-300 text-xl"></i>
                        </div>

                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 flex items-center justify-between">
                            <div>
                                <span class="text-[9px] uppercase font-bold text-slate-400 block tracking-wider">Nomor Rekening</span>
                                <span class="text-base font-mono font-bold text-slate-900 select-all">{{ $settings['account_number'] ?? '12345678' }}</span>
                            </div>
                            <button type="button" onclick="navigator.clipboard.writeText('{{ $settings['account_number'] ?? '12345678' }}'); alert('No. Rekening disalin!');" class="text-teal-700 hover:text-teal-900 text-xs font-bold bg-teal-50 px-2.5 py-1.5 rounded-lg transition">
                                <i class="fas fa-copy"></i> Salin
                            </button>
                        </div>

                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 flex items-center justify-between">
                            <div>
                                <span class="text-[9px] uppercase font-bold text-slate-400 block tracking-wider">Atas Nama</span>
                                <span class="text-sm font-bold text-slate-900">{{ $settings['account_name'] ?? 'MedLogix' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 text-[10px] text-teal-800 flex items-start gap-2 bg-teal-50 p-3.5 rounded-xl border border-teal-200">
                    <i class="fas fa-info-circle text-teal-650 mt-0.5 text-xs"></i>
                    <span>Setelah transfer, lampirkan bukti pembayaran yang valid dalam format gambar (PNG/JPG) di form sebelah kanan.</span>
                </div>
            </div>

            <!-- Pricing Summary & Proof Upload -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <h3 class="text-md font-black text-slate-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-receipt text-teal-850"></i>
                        Rincian &amp; Bukti Pembayaran
                    </h3>

                    <!-- Pricing Calculation Box -->
                    <div class="bg-slate-900 text-white rounded-2xl p-5 mb-6 shadow-sm">
                        <div class="space-y-2 text-xs border-b border-white/10 pb-3 mb-3 text-slate-300">
                            <div class="flex justify-between">
                                <span>Biaya Paket Pilihan:</span>
                                <span id="plan-cost-display" class="font-mono text-white font-bold">Rp 30.000</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Biaya Ekstra Slot:</span>
                                <span id="addon-cost-display" class="font-mono text-white font-bold">Rp 0</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-bold text-slate-200">Total Pembayaran:</span>
                            <span id="total-cost-display" class="text-xl font-black font-mono text-teal-400">Rp 30.000</span>
                        </div>
                    </div>

                    <!-- Receipt Upload View -->
                    @if ($user->payment_status === 'pending' && $user->payment_receipt)
                        <div class="border-2 border-dashed border-slate-200 rounded-2xl p-4 flex flex-col items-center justify-center text-center">
                            <span class="text-xs font-semibold text-slate-500 mb-3">
                                Bukti transfer dalam peninjauan:
                            </span>
                            <div class="relative group max-w-[150px] rounded-xl overflow-hidden shadow-sm border border-slate-200">
                                <img src="{{ asset($user->payment_receipt) }}" alt="Bukti Pembayaran" class="h-28 w-auto object-cover filter brightness-75 hover:brightness-100 transition">
                                <a href="{{ asset($user->payment_receipt) }}" target="_blank" class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 text-white font-bold text-xs transition">
                                    <i class="fas fa-expand mr-1"></i> Perbesar
                                </a>
                            </div>
                            <div class="mt-3 bg-amber-50 text-amber-800 text-[10px] px-3 py-1 rounded-full font-bold border border-amber-200">
                                Menunggu Verifikasi Superadmin
                            </div>
                        </div>
                    @else
                        <!-- Form File Input -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-550 uppercase tracking-wider mb-2">Upload Bukti Transfer</label>
                                
                                <div class="relative border-2 border-dashed border-slate-300 hover:border-teal-500 rounded-2xl p-5 text-center cursor-pointer transition" onclick="document.getElementById('file-upload-input').click()">
                                    <input id="file-upload-input" type="file" name="payment_receipt" accept="image/*" class="hidden" onchange="previewFile(event)">
                                    <div class="space-y-1.5">
                                        <i class="fas fa-cloud-upload-alt text-teal-700 text-2xl"></i>
                                        <div class="text-xs text-slate-650">
                                            <span class="font-bold text-teal-700">Klik untuk upload</span> atau seret bukti transfer
                                        </div>
                                        <p class="text-[9px] text-slate-400">PNG, JPG, JPEG maks. 2MB</p>
                                    </div>
                                </div>
                                
                                <!-- Preview Area -->
                                <div id="preview-container" class="hidden mt-3 border border-slate-200 p-2.5 rounded-xl flex items-center justify-between">
                                    <div class="flex items-center gap-2.5">
                                        <img id="image-preview" src="#" alt="Preview" class="w-10 h-10 rounded-lg object-cover shadow-sm border border-slate-200">
                                        <div class="text-left">
                                            <span id="file-name" class="text-xs font-bold text-slate-800 block truncate max-w-[130px]">receipt.jpg</span>
                                            <span class="text-[9px] text-emerald-500 font-semibold"><i class="fas fa-check-circle"></i> Terpilih</span>
                                        </div>
                                    </div>
                                    <button type="button" onclick="removeSelectedFile(event)" class="text-red-750 hover:text-red-800 bg-red-50 p-2 rounded-lg text-xs font-bold transition">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>

                                @error('payment_receipt')
                                    <span class="text-xs text-red-500 font-semibold mt-1.5 block">
                                        <i class="fas fa-triangle-exclamation mr-1"></i> {{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <button type="submit" class="w-full bg-teal-900 hover:bg-teal-800 text-white font-bold py-3 rounded-xl transition shadow-md hover:shadow-lg text-sm flex items-center justify-center gap-2">
                                <i class="fas fa-paper-plane"></i> Kirim & Aktifkan Paket
                            </button>
                        </div>
                    @endif
                </div>

                @if ($user->payment_status === 'paid' || $user->subscription_plan === 'trial')
                    <div class="mt-6">
                        <a href="{{ route('dashboard') }}" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold py-2.5 rounded-xl transition text-center text-xs block">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Dashboard
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </form>
</div>

<script>
    const priceMonthly = {{ (int)($settings['price_monthly'] ?? 30000) }};
    const priceYearly = {{ (int)($settings['price_yearly'] ?? 300000) }};
    const priceAddonSlot = {{ (int)($settings['price_addon_slot'] ?? 30000) }};

    function updatePricing() {
        const planElement = document.querySelector('input[name="pending_plan"]:checked');
        const plan = planElement ? planElement.value : 'monthly';
        const addonQty = parseInt(document.getElementById('pending_addon_qty').value) || 0;

        let planPrice = 0;
        if (plan === 'monthly') {
            planPrice = priceMonthly;
        } else if (plan === 'yearly') {
            planPrice = priceYearly;
        }

        const addonPrice = Math.floor(addonQty / 10) * priceAddonSlot;
        const totalPrice = planPrice + addonPrice;

        document.getElementById('plan-cost-display').innerText = 'Rp ' + planPrice.toLocaleString('id-ID');
        document.getElementById('addon-cost-display').innerText = 'Rp ' + addonPrice.toLocaleString('id-ID');
        document.getElementById('total-cost-display').innerText = 'Rp ' + totalPrice.toLocaleString('id-ID');

        // Toggle UI active classes for cards
        const bulletMonthly = document.getElementById('bullet-monthly');
        const bulletYearly = document.getElementById('bullet-yearly');

        if (plan === 'monthly') {
            bulletMonthly.classList.add('bg-teal-600', 'border-teal-600');
            bulletMonthly.querySelector('i').classList.remove('opacity-0');
            bulletYearly.classList.remove('bg-teal-600', 'border-teal-600');
            bulletYearly.querySelector('i').classList.add('opacity-0');
        } else {
            bulletYearly.classList.add('bg-teal-600', 'border-teal-600');
            bulletYearly.querySelector('i').classList.remove('opacity-0');
            bulletMonthly.classList.remove('bg-teal-600', 'border-teal-600');
            bulletMonthly.querySelector('i').classList.add('opacity-0');
        }
    }

    function adjustAddon(amount) {
        const input = document.getElementById('pending_addon_qty');
        let current = parseInt(input.value) || 0;
        current += amount;
        if (current < 0) {
            current = 0;
        }
        input.value = current;
        updatePricing();
    }

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

    // Run pricing initialization
    document.addEventListener('DOMContentLoaded', function() {
        updatePricing();
    });
</script>
@endsection
