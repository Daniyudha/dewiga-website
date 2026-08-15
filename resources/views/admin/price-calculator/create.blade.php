@php
    use App\Models\PricingComponent;
    $snackComponent = PricingComponent::where('code', 'snack')->where('is_active', true)->first();
    $snackPrice = $snackComponent ? $snackComponent->default_price : 15000;
    $snackActive = $snackComponent && $snackComponent->is_active;
@endphp
@extends('layouts.app')

@section('title', 'Estimasi Baru - Kalkulator Harga')

@section('content')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-heading font-bold text-gray-900">Estimasi Baru</h1>
        <p class="text-sm text-gray-500 mt-1">Hitung estimasi biaya paket wisata untuk rombongan</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.price-calculator.index') }}" class="admin-btn-secondary admin-btn-sm">
            <i class="fas fa-arrow-left mr-1"></i>
            Kembali
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Calculator Form --}}
    <div class="lg:col-span-2">
        <form id="calculatorForm" method="POST" action="{{ route('admin.price-calculator.store') }}" class="space-y-6">
            @csrf
            
        {{-- Section A: Informasi Rombongan --}}
        <div class="admin-card shadow-md">
            <div class="admin-card-header">
                <h3 class="font-heading font-semibold text-gray-800">
                    <i class="fas fa-school text-primary-600 mr-2"></i>
                    Informasi Rombongan
                </h3>
            </div>
            <div class="admin-card-body space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="admin-label">Nama Sekolah / Instansi <span class="text-red-500">*</span></label>
                        <input type="text" name="institution_name" id="institution_name" class="admin-input" required placeholder="Contoh: SMA CITA HATI">
                    </div>
                    <div>
                        <label class="admin-label">Nama Penanggung Jawab <span class="text-red-500">*</span></label>
                        <input type="text" name="contact_person" id="contact_person" class="admin-input" required placeholder="Nama guru / pendamping">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="admin-label">Nomor WhatsApp <span class="text-red-500">*</span></label>
                        <input type="text" name="whatsapp" id="whatsapp" class="admin-input" required placeholder="08xxxxxxxxxx">
                    </div>
                    <div>
                        <label class="admin-label">Catatan</label>
                        <input type="text" name="notes" id="notes" class="admin-input" placeholder="Catatan tambahan">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="admin-label">Tanggal Kedatangan <span class="text-red-500">*</span></label>
                        <input type="date" name="arrival_date" id="arrival_date" class="admin-input" required>
                    </div>
                    <div>
                        <label class="admin-label">Tanggal Kepulangan <span class="text-red-500">*</span></label>
                        <input type="date" name="departure_date" id="departure_date" class="admin-input" required>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section B: Data Peserta --}}
        <div class="admin-card shadow-md">
            <div class="admin-card-header">
                <h3 class="font-heading font-semibold text-gray-800">
                    <i class="fas fa-users text-primary-600 mr-2"></i>
                    Data Peserta
                </h3>
            </div>
            <div class="admin-card-body space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="admin-label">Jumlah Siswa <span class="text-red-500">*</span></label>
                        <input type="number" name="student_count" id="student_count" class="admin-input" required min="1" value="0">
                    </div>
                    <div>
                        <label class="admin-label">Jumlah Pendamping</label>
                        <input type="number" name="companion_count" id="companion_count" class="admin-input" min="0" value="0">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="admin-label">Jumlah Peserta Layanan Utama <span class="text-red-500">*</span></label>
                        <input type="number" name="service_participant_count" id="service_participant_count" class="admin-input" required min="1" value="0" disabled>
                        <p class="text-xs text-gray-400 mt-1">Default: jumlah siswa + pendamping. Dapat diubah manual.</p>
                    </div>
                    <div>
                        <label class="admin-label">Jumlah Peserta Kegiatan</label>
                        <input type="number" name="activity_participant_count" id="activity_participant_count" class="admin-input" min="0" value="0" disabled>
                        <p class="text-xs text-gray-400 mt-1">Default: jumlah siswa. Dapat diubah manual.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section C: Komponen Paket Utama --}}
        <div class="admin-card shadow-md">
            <div class="admin-card-header">
                <h3 class="font-heading font-semibold text-gray-800">
                    <i class="fas fa-box text-primary-600 mr-2"></i>
                    Komponen Paket Utama
                </h3>
            </div>
            <div class="admin-card-body space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="admin-label">Jumlah Malam Live In</label>
                        <input type="number" name="live_in_nights" id="live_in_nights" class="admin-input" min="0" value="0">
                    </div>
                    <div>
                        <label class="admin-label">Frekuensi Makan</label>
                        <input type="number" name="meal_count" id="meal_count" class="admin-input" min="0" value="0">
                    </div>
                    <div>
                        <label class="admin-label">Jumlah Kegiatan Reguler</label>
                        <input type="number" name="regular_activity_count" id="regular_activity_count" class="admin-input" min="0" value="0">
                    </div>
                    <div>
                        <label class="admin-label">Jumlah Sesi Kegiatan Kesenian Peserta</label>
                        <input type="number" name="art_sessions" id="art_sessions" class="admin-input" min="0" value="0">
                    </div>
                </div>
            </div>
        </div>

        {{-- Section D: Snack --}}
        @if($snackActive)
        <div class="admin-card shadow-md">
            <div class="admin-card-header">
                <h3 class="font-heading font-semibold text-gray-800">
                    <i class="fas fa-cookie text-primary-600 mr-2"></i>
                    Snack
                </h3>
            </div>
            <div class="admin-card-body space-y-4">
                <div>
                    <label class="admin-label">Frekuensi Snack</label>
                    <input type="number" name="snack_count" id="snack_count" class="admin-input" min="0" value="0" placeholder="Jumlah kali snack">
                    <p class="text-xs text-gray-400 mt-1">Harga snack per orang per kali: {{ formatPrice($snackPrice) }} (dapat diubah di Pengaturan Harga)</p>
                </div>
            </div>
        </div>
        @endif

        {{-- Section E: Lomba Masak --}}
        <div class="admin-card shadow-md">
            <div class="admin-card-header">
                <h3 class="font-heading font-semibold text-gray-800">
                    <i class="fas fa-utensils text-primary-600 mr-2"></i>
                    Lomba Masak
                </h3>
            </div>
            <div class="admin-card-body space-y-4">
                <div class="flex items-center gap-3">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="cooking_active" id="cooking_active" value="1" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                    </label>
                    <span class="text-sm font-medium text-gray-700">Aktifkan Lomba Masak</span>
                </div>
                <div id="cookingFields" class="grid grid-cols-1 md:grid-cols-2 gap-4 hidden">
                    <div>
                        <label class="admin-label">Jumlah Peserta Lomba Masak</label>
                        <input type="number" name="cooking_participants" id="cooking_participants" class="admin-input" min="0" value="0">
                    </div>
                    <div>
                        <label class="admin-label">Kapasitas per Kelompok</label>
                        <input type="number" name="cooking_capacity" id="cooking_capacity" class="admin-input" min="1" value="10">
                    </div>
                    <div>
                        <label class="admin-label">Harga per Kelompok (Rp)</label>
                        <input type="number" name="cooking_price_per_group" id="cooking_price_per_group" class="admin-input" min="0" value="100000">
                    </div>
                    <div>
                        <label class="admin-label">Jumlah Kelompok (Manual, opsional)</label>
                        <input type="number" name="cooking_manual_groups" id="cooking_manual_groups" class="admin-input" min="0" placeholder="Biarkan kosong untuk otomatis">
                    </div>
                </div>
            </div>
        </div>

        {{-- Section F: Add-on --}}
        <div class="admin-card shadow-md">
            <div class="admin-card-header">
                <h3 class="font-heading font-semibold text-gray-800">
                    <i class="fas fa-plus-circle text-primary-600 mr-2"></i>
                    Add-on
                </h3>
            </div>
            <div class="admin-card-body space-y-6">

                {{-- F1: Transportasi --}}
                <div>
                    <h4 class="font-medium text-gray-700 mb-3 flex items-center gap-2">
                        <i class="fas fa-bus text-primary-500"></i>
                        Transportasi
                    </h4>
                    <div class="flex items-center gap-3 mb-3">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="pickup_active" id="pickup_active" value="1" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                        </label>
                        <span class="text-sm font-medium text-gray-700">Pickup Wisata</span>
                    </div>
                    <div id="pickupFields" class="grid grid-cols-1 md:grid-cols-2 gap-4 hidden">
                        <div>
                            <label class="admin-label">Jumlah Pengguna Pickup</label>
                            <input type="number" name="pickup_users" id="pickup_users" class="admin-input" min="0" value="0">
                        </div>
                        <div>
                            <label class="admin-label">Jumlah Unit (Manual, opsional)</label>
                            <input type="number" name="pickup_manual_units" id="pickup_manual_units" class="admin-input" min="0" placeholder="Kosong untuk otomatis">
                        </div>
                    </div>
                </div>

                {{-- F2: Kesenian dan Hiburan --}}
                <div>
                    <h4 class="font-medium text-gray-700 mb-3 flex items-center gap-2">
                        <i class="fas fa-music text-primary-500"></i>
                        Kesenian & Hiburan
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="admin-label">Pertunjukan Kesenian</label>
                            <input type="number" name="cultural_performances" id="cultural_performances" class="admin-input" min="0" value="0" placeholder="Jumlah penampilan">
                        </div>
                        <div>
                            <label class="admin-label">Live Music / Organ Tunggal</label>
                            <input type="number" name="live_music_performances" id="live_music_performances" class="admin-input" min="0" value="0" placeholder="Jumlah penampilan">
                        </div>
                        <div class="md:col-span-2">
                            <label class="admin-label">Opsi Sound & Lighting</label>
                            <select name="sound_lighting_option" id="sound_lighting_option" class="admin-input">
                                <option value="none">Tanpa Sound & Lighting</option>
                                <option value="sound_only">Sound Profesional Saja (Rp700.000)</option>
                                <option value="lighting_only">Lighting Panggung Saja (Rp2.000.000)</option>
                                <option value="package">Paket Sound + Lighting (Rp2.500.000)</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- F3: Add-on Lainnya (dinamis, multiple) --}}
                <div>
                    <h4 class="font-medium text-gray-700 mb-3 flex items-center gap-2">
                        <i class="fas fa-box-open text-primary-500"></i>
                        Add-on Lainnya
                    </h4>
                    <div class="flex items-center gap-3 mb-3">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="other_addon_active" id="other_addon_active" value="1" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                        </label>
                        <span class="text-sm font-medium text-gray-700">Aktifkan Add-on Lainnya</span>
                    </div>
                    <div id="otherAddonFields" class="hidden space-y-3">
                        <div id="addonItemsContainer" class="space-y-3"></div>
                        <button type="button" id="addAddonItemBtn" class="admin-btn-secondary admin-btn-sm mt-2">
                            <i class="fas fa-plus mr-1"></i>
                            Tambah Add-on
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section G: Pembulatan --}}
        <div class="admin-card shadow-md">
            <div class="admin-card-header">
                <h3 class="font-heading font-semibold text-gray-800">
                    <i class="fas fa-calculator text-primary-600 mr-2"></i>
                    Pembulatan Harga
                </h3>
            </div>
            <div class="admin-card-body space-y-4">
                <div>
                    <label class="admin-label">Pilihan Pembulatan</label>
                    <select name="rounding_type" id="rounding_type" class="admin-input">
                        <option value="none">Tanpa Pembulatan</option>
                        <option value="up_1000">Ke atas Rp1.000</option>
                        <option value="up_5000">Ke atas Rp5.000</option>
                        <option value="up_10000">Ke atas Rp10.000</option>
                        <option value="down_1000">Ke bawah Rp1.000</option>
                        <option value="down_5000">Ke bawah Rp5.000</option>
                        <option value="down_10000">Ke bawah Rp10.000</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="hidden lg:flex flex-wrap gap-3">
            <button type="button" id="calculateBtn" class="admin-btn-primary">
                <i class="fas fa-calculator mr-2"></i>
                Hitung Estimasi
            </button>
            <button type="reset" class="admin-btn-secondary">
                <i class="fas fa-undo mr-2"></i>
                Reset Form
            </button>
            <button type="submit" id="saveBtn" class="admin-btn-success" disabled>
                <i class="fas fa-save mr-2"></i>
                Simpan Estimasi
            </button>
        </div>
    </form>
</div>

    {{-- Results Panel --}}
    <div class="lg:col-span-1 space-y-6 py-6">
        <div class="admin-card shadow-md">
            <div class="admin-card-header">
                <h3 class="font-heading font-semibold text-gray-800">
                    <i class="fas fa-file-invoice text-primary-600 mr-2"></i>
                    Ringkasan Estimasi
                </h3>
            </div>
            <div class="admin-card-body" id="resultsContainer">
                <div class="text-center py-8 text-gray-400">
                    <i class="fas fa-calculator text-4xl mb-3"></i>
                    <p>Klik "Hitung Estimasi" untuk melihat hasil</p>
                </div>
            </div>
        </div>
        {{-- Actions --}}
        <div class="lg:hidden flex-wrap gap-3 space-x-0 space-y-3">
            <button type="button" id="calculateBtn" class="admin-btn-primary w-full">
                <i class="fas fa-calculator mr-2"></i>
                Hitung Estimasi
            </button>
            <button type="submit" class="admin-btn-success w-full">
                <i class="fas fa-save mr-2"></i>
                {{ isset($duplicate) && $duplicate ? 'Duplikasi Estimasi' : 'Simpan Perubahan' }}
            </button>
            <a href="{{ route('admin.price-calculator.index') }}" class="admin-btn-secondary w-full">
                <i class="fas fa-times mr-2"></i>
                Batal
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const calculatorForm = document.getElementById('calculatorForm');
const calculateBtn = document.getElementById('calculateBtn');
const saveBtn = document.getElementById('saveBtn');
const resultsContainer = document.getElementById('resultsContainer');

let lastCalculationResult = null;
let calculateTimeout = null;
let customItemIndex = 0;

function autoCalcServiceParticipants() {
    const students = parseInt(document.getElementById('student_count').value) || 0;
    const companions = parseInt(document.getElementById('companion_count').value) || 0;
    const spc = document.getElementById('service_participant_count');
    if (!spc.dataset.manual) { spc.value = students + companions; }
}

function autoCalcActivityParticipants() {
    const students = parseInt(document.getElementById('student_count').value) || 0;
    const apc = document.getElementById('activity_participant_count');
    if (!apc.dataset.manual) { apc.value = students; }
}

document.getElementById('student_count').addEventListener('input', function() {
    delete document.getElementById('service_participant_count').dataset.manual;
    delete document.getElementById('activity_participant_count').dataset.manual;
    autoCalcServiceParticipants(); autoCalcActivityParticipants(); debouncedCalculate();
});
document.getElementById('companion_count').addEventListener('input', function() {
    delete document.getElementById('service_participant_count').dataset.manual;
    autoCalcServiceParticipants(); debouncedCalculate();
});
document.getElementById('service_participant_count').addEventListener('input', function() {
    this.dataset.manual = '1'; debouncedCalculate();
});
document.getElementById('activity_participant_count').addEventListener('input', function() {
    this.dataset.manual = '1'; debouncedCalculate();
});

document.getElementById('cooking_active').addEventListener('change', function() {
    document.getElementById('cookingFields').classList.toggle('hidden', !this.checked); debouncedCalculate();
});
document.getElementById('pickup_active').addEventListener('change', function() {
    document.getElementById('pickupFields').classList.toggle('hidden', !this.checked); debouncedCalculate();
});
document.getElementById('other_addon_active').addEventListener('change', function() {
    document.getElementById('otherAddonFields').classList.toggle('hidden', !this.checked); debouncedCalculate();
});
document.getElementById('rounding_type').addEventListener('change', function() {
    if (lastCalculationResult) debouncedCalculate();
});

document.querySelectorAll('#calculatorForm input, #calculatorForm select').forEach(el => {
    el.addEventListener('change', debouncedCalculate);
    if (el.type === 'number') el.addEventListener('input', debouncedCalculate);
});

function debouncedCalculate() {
    clearTimeout(calculateTimeout);
    calculateTimeout = setTimeout(() => performCalculation(), 500);
}

function getAddonItemsData() {
    const items = [];
    document.querySelectorAll('.addon-item-row').forEach(row => {
        const name = row.querySelector('.ao-name').value?.trim();
        const price = parseFloat(row.querySelector('.ao-price').value) || 0;
        const qty = parseInt(row.querySelector('.ao-qty').value) || 1;
        if (name && price > 0) items.push({ name, unit_price: price, quantity: qty });
    });
    return items;
}

async function performCalculation() {
    const formData = new FormData(calculatorForm);
    const data = {};
    formData.forEach((value, key) => { data[key] = value; });
    data.cooking_active = document.getElementById('cooking_active').checked ? '1' : '0';
    data.pickup_active = document.getElementById('pickup_active').checked ? '1' : '0';
    data.other_addon_active = document.getElementById('other_addon_active').checked ? '1' : '0';
    data.rounding_type = document.getElementById('rounding_type').value;
    data.addon_items = getAddonItemsData();

    try {
        const response = await fetch('{{ url('admin/price-calculator/calculate') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        });
        if (!response.ok) {
            const errData = await response.json();
            if (errData.errors) showToast(Object.values(errData.errors).flat().join('<br>'), 'error');
            return;
        }
        const result = await response.json();
        lastCalculationResult = result;
        renderResults(result);
        saveBtn.disabled = false;
    } catch (error) {
        showToast('Gagal melakukan perhitungan', 'error');
    }
}

function formatCurrency(amount) {
    return 'Rp ' + Math.round(amount).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function renderResults(result) {
    if (!result.items || result.items.length === 0) {
        resultsContainer.innerHTML = '<div class="text-center py-8 text-gray-400"><i class="fas fa-calculator text-4xl mb-3"></i><p>Klik "Hitung Estimasi" untuk melihat hasil</p></div>';
        return;
    }
    let html = '<div class="overflow-x-auto"><table class="admin-table text-xs"><thead><tr><th>Komponen</th><th>Qty</th><th>Frekuensi</th><th>Harga Satuan</th><th>Harga/Orang</th><th>Jumlah</th></tr></thead><tbody>';
    result.items.forEach(item => {
        html += `<tr><td class="font-medium">${item.name}</td><td>${item.quantity}</td><td>${item.frequency} ${item.unit}</td><td class="font-mono">${formatCurrency(item.unit_price)}</td><td class="font-mono">${formatCurrency(item.price_per_person)}</td><td class="font-mono font-semibold">${formatCurrency(item.total)}</td></tr>`;
    });
    html += `</tbody></table></div>`;
    html += `<div class="mt-4 space-y-2 border-t pt-4">
        <div class="flex justify-between text-sm"><span class="text-gray-600">Grand Total Biaya:</span><span class="font-bold font-mono">${formatCurrency(result.subtotal)}</span></div>
        <div class="flex justify-between text-sm"><span class="text-gray-600">Harga Aktual per Orang:</span><span class="font-mono">${formatCurrency(result.actual_price_per_person)}</span></div>
        <div class="flex justify-between text-sm"><span class="text-gray-600">Harga per Orang ${result.rounding_type === 'none' ? '' : '(Setelah Pembulatan)'}:</span><span class="font-bold font-mono text-primary-600">${formatCurrency(result.rounded_price_per_person)}</span></div>
        <div class="flex justify-between text-sm"><span class="text-gray-600">Total Quotation:</span><span class="font-bold font-mono text-primary-600">${formatCurrency(result.quotation_total)}</span></div>
        <div class="flex justify-between text-sm"><span class="text-gray-600">Selisih Pembulatan:</span><span class="font-mono ${result.difference_amount >= 0 ? 'text-green-600' : 'text-red-600'}">${result.difference_amount >= 0 ? '+' : ''}${formatCurrency(result.difference_amount)}</span></div>
    </div>`;
    resultsContainer.innerHTML = html;
}

calculateBtn.addEventListener('click', performCalculation);

let addonItemIndex = 0;
document.getElementById('addAddonItemBtn')?.addEventListener('click', function() {
    addonItemIndex++;
    const container = document.getElementById('addonItemsContainer');
    const row = document.createElement('div');
    row.className = 'addon-item-row bg-gray-50 rounded-lg p-4 border border-gray-200 space-y-3';
    row.innerHTML = `
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-medium text-gray-700">Add-on ${addonItemIndex}</span>
            <button type="button" class="admin-btn-sm admin-btn-danger" onclick="this.closest('.addon-item-row').remove(); debouncedCalculate();"><i class="fas fa-trash"></i> Hapus</button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div><label class="admin-label text-xs">Nama Add-on</label><input type="text" class="admin-input ao-name" placeholder="Nama add-on"></div>
            <div><label class="admin-label text-xs">Harga Satuan (Rp)</label><input type="number" class="admin-input ao-price" min="0" value="0" step="1000"></div>
            <div><label class="admin-label text-xs">Jumlah</label><input type="number" class="admin-input ao-qty" min="1" value="1"></div>
        </div>`;
    container.appendChild(row);
    row.querySelectorAll('input').forEach(el => el.addEventListener('input', debouncedCalculate));
});
</script>
@endpush