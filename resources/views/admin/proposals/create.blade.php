@extends('layouts.app')

@section('title', 'Buat Proposal Program')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-heading font-bold text-gray-900">Buat Proposal Program</h1>
    <a href="{{ route('admin.proposals.index') }}" class="admin-btn-sm admin-btn-secondary">
        <i class="fas fa-arrow-left mr-1"></i> Kembali
    </a>
</div>

{{-- Tab: Pilih Metode --}}
<div class="border-b border-gray-200 mb-6">
    <nav class="-mb-px flex space-x-6">
        <button onclick="switchMethod('dari_estimasi')" class="tab-method active px-4 py-3 text-sm font-medium border-b-2" data-method="dari_estimasi" type="button" style="color:#059669;border-bottom-color:#059669;font-weight:600;">
            <i class="fas fa-calculator mr-1"></i> Dari Kalkulator Harga
        </button>
        <button onclick="switchMethod('baru')" class="tab-method px-4 py-3 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-700" data-method="baru" type="button">
            <i class="fas fa-plus-circle mr-1"></i> Buat Baru
        </button>
    </nav>
</div>

{{-- Method: Dari Estimasi yang Ada --}}
<div id="method-dari_estimasi" class="method-content">
    <div class="admin-card shadow-md">
        <div class="admin-card-header">
            <h3 class="font-heading font-semibold text-gray-800">
                <i class="fas fa-file-invoice text-primary-600 mr-2"></i> Pilih Estimasi yang Ada
            </h3>
        </div>
        <div class="admin-card-body">
            <p class="text-sm text-gray-500 mb-4">Pilih estimasi harga yang sudah dibuat sebelumnya untuk dijadikan proposal.</p>

            @php $estimations = \App\Models\PriceEstimation::with('createdBy')->orderBy('created_at', 'desc')->take(20)->get(); @endphp
            @if($estimations->count() > 0)
                <div class="space-y-2">
                    @foreach($estimations as $est)
                    <a href="{{ route('admin.proposals.convert-estimation', $est) }}" class="block border border-gray-200 rounded-lg p-4 hover:bg-gray-50 hover:border-primary-300 transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="font-medium text-gray-900">{{ $est->institution_name }}</span>
                                <span class="text-sm text-gray-500 ml-3 font-mono">{{ $est->estimation_number }}</span>
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $est->service_participant_count }} peserta
                                &middot; {{ $est->arrival_date->format('d/m/Y') }}
                                &middot; <span class="font-mono">{{ formatPrice($est->quotation_total) }}</span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-6 text-gray-400">
                    <i class="fas fa-calculator text-4xl mb-2"></i>
                    <p>Belum ada estimasi harga. Buat estimasi dulu di Kalkulator Harga.</p>
                </div>
            @endif

            <div class="mt-4 pt-4 border-t text-center">
                <a href="{{ route('admin.price-calculator.create') }}" class="admin-btn-sm admin-btn-primary" target="_blank">
                    <i class="fas fa-external-link-alt mr-1"></i> Buka Kalkulator Harga
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Method: Buat Baru --}}
<div id="method-baru" class="method-content hidden">
    <form method="POST" action="{{ route('admin.proposals.store') }}" id="proposalForm">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                {{-- Informasi Proposal --}}
                <div class="admin-card shadow-md">
                    <div class="admin-card-header">
                        <h3 class="font-heading font-semibold text-gray-800">
                            <i class="fas fa-file-invoice text-primary-600 mr-2"></i> Informasi Proposal
                        </h3>
                    </div>
                    <div class="admin-card-body space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="admin-label">Judul Proposal</label>
                                <input type="text" name="proposal_title" value="{{ old('proposal_title') }}" class="admin-input w-full shadow-md rounded-md border border-gray-300" placeholder="Program Kunjungan Edukasi...">
                            </div>
                            <div>
                                <label class="admin-label">Template Rundown</label>
                                <select name="rundown_template_id" class="admin-input w-full shadow-md rounded-md border border-gray-300">
                                    <option value="">-- Tanpa Template --</option>
                                    @foreach($templates as $t)
                                        <option value="{{ $t->id }}" {{ old('rundown_template_id') == $t->id ? 'selected' : '' }}>{{ $t->name }} ({{ $t->duration_days }}H)</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="admin-label">Tujuan Program</label>
                            <textarea name="program_objective" rows="2" class="admin-input w-full shadow-md rounded-md border border-gray-300" placeholder="Tujuan dari program kunjungan ini...">{{ old('program_objective') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Pilihan Kegiatan --}}
                <div class="admin-card shadow-md">
                    <div class="admin-card-header">
                        <h3 class="font-heading font-semibold text-gray-800">
                            <i class="fas fa-running text-primary-600 mr-2"></i> Pilihan Kegiatan
                        </h3>
                    </div>
                    <div class="admin-card-body">
                        <p class="text-sm text-gray-500 mb-4">Pilih kegiatan yang akan dimasukkan dalam proposal.</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            @php $activities = \App\Models\Activity::orderBy('order')->get(); @endphp
                            @foreach($activities as $activity)
                            <label class="flex items-center gap-2 p-2 border border-gray-200 rounded hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="selected_activities[]" value="{{ $activity->id }}" class="rounded border-gray-300 text-primary-600">
                                <span class="text-sm">{{ $activity->title_id }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Informasi Rombongan --}}
                <div class="admin-card shadow-md">
                    <div class="admin-card-header">
                        <h3 class="font-heading font-semibold text-gray-800">
                            <i class="fas fa-school text-primary-600 mr-2"></i> Informasi Rombongan
                        </h3>
                    </div>
                    <div class="admin-card-body">
                        <p class="text-sm text-amber-600 bg-amber-50 p-3 rounded mb-4">
                            <i class="fas fa-info-circle mr-1"></i> 
                            Isi data rombongan terlebih dahulu, kemudian buka Kalkulator Harga untuk menghitung biaya.
                            Setelah selesai, kembali ke halaman ini dan pilih estimasi dari tab "Dari Kalkulator Harga".
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="admin-label">Nama Sekolah / Instansi <span class="text-red-500">*</span></label>
                                <input type="text" name="institution_name" value="{{ old('institution_name') }}" class="admin-input w-full shadow-md rounded-md border border-gray-300" required>
                            </div>
                            <div>
                                <label class="admin-label">Penanggung Jawab <span class="text-red-500">*</span></label>
                                <input type="text" name="contact_person" value="{{ old('contact_person') }}" class="admin-input w-full shadow-md rounded-md border border-gray-300" required>
                            </div>
                            <div>
                                <label class="admin-label">No. WhatsApp <span class="text-red-500">*</span></label>
                                <input type="text" name="whatsapp" value="{{ old('whatsapp') }}" class="admin-input w-full shadow-md rounded-md border border-gray-300" required>
                            </div>
                            <div>
                                <label class="admin-label">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" class="admin-input w-full shadow-md rounded-md border border-gray-300">
                            </div>
                            <div>
                                <label class="admin-label">Tanggal Kedatangan <span class="text-red-500">*</span></label>
                                <input type="date" name="arrival_date" value="{{ old('arrival_date') }}" class="admin-input w-full shadow-md rounded-md border border-gray-300" required>
                            </div>
                            <div>
                                <label class="admin-label">Tanggal Kepulangan <span class="text-red-500">*</span></label>
                                <input type="date" name="departure_date" value="{{ old('departure_date') }}" class="admin-input w-full shadow-md rounded-md border border-gray-300" required>
                            </div>
                            <div>
                                <label class="admin-label">Jumlah Siswa <span class="text-red-500">*</span></label>
                                <input type="number" name="student_count" value="{{ old('student_count', 0) }}" class="admin-input w-full shadow-md rounded-md border border-gray-300" min="0" required>
                            </div>
                            <div>
                                <label class="admin-label">Jumlah Pendamping</label>
                                <input type="number" name="companion_count" value="{{ old('companion_count', 0) }}" class="admin-input w-full shadow-md rounded-md border border-gray-300" min="0">
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="admin-label">Catatan / Kebutuhan Khusus</label>
                            <textarea name="notes" rows="2" class="admin-input w-full shadow-md rounded-md border border-gray-300">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Paket Program (sederhana) --}}
                <div class="admin-card shadow-md">
                    <div class="admin-card-header">
                        <h3 class="font-heading font-semibold text-gray-800">
                            <i class="fas fa-cube text-primary-600 mr-2"></i> Paket Program (Opsional)
                        </h3>
                    </div>
                    <div class="admin-card-body">
                        <p class="text-sm text-gray-500 mb-4">
                            Isi paket program secara manual jika belum membuat estimasi di Kalkulator Harga.
                            <strong>Rekomendasi:</strong> Gunakan Kalkulator Harga untuk perhitungan yang lebih akurat.
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="admin-label">Live In (malam)</label>
                                <input type="number" name="live_in_nights" value="{{ old('live_in_nights', 0) }}" class="admin-input w-full shadow-md rounded-md border border-gray-300" min="0">
                            </div>
                            <div>
                                <label class="admin-label">Makan (kali)</label>
                                <input type="number" name="meal_count" value="{{ old('meal_count', 0) }}" class="admin-input w-full shadow-md rounded-md border border-gray-300" min="0">
                            </div>
                            <div>
                                <label class="admin-label">Snack (kali)</label>
                                <input type="number" name="snack_count" value="{{ old('snack_count', 0) }}" class="admin-input w-full shadow-md rounded-md border border-gray-300" min="0">
                            </div>
                            <div>
                                <label class="admin-label">Kegiatan Reguler</label>
                                <input type="number" name="regular_activity_count" value="{{ old('regular_activity_count', 0) }}" class="admin-input w-full shadow-md rounded-md border border-gray-300" min="0">
                            </div>
                            <div>
                                <label class="admin-label">Sesi Kesenian</label>
                                <input type="number" name="art_sessions" value="{{ old('art_sessions', 0) }}" class="admin-input w-full shadow-md rounded-md border border-gray-300" min="0">
                            </div>
                            <div>
                                <label class="admin-label">Pertunjukan Kesenian</label>
                                <input type="number" name="cultural_performances" value="{{ old('cultural_performances', 0) }}" class="admin-input w-full shadow-md rounded-md border border-gray-300" min="0">
                            </div>
                        </div>
                        <div class="mt-3">
                            <label class="admin-label">Pembulatan</label>
                            <select name="rounding_type" class="admin-input w-full shadow-md rounded-md border border-gray-300">
                                <option value="none">Tanpa Pembulatan</option>
                                <option value="up_1000">Ke atas Rp1.000</option>
                                <option value="up_5000">Ke atas Rp5.000</option>
                                <option value="up_10000">Ke atas Rp10.000</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="admin-card shadow-md">
                    <div class="admin-card-body space-y-3">
                        <p class="text-sm text-gray-500 mb-2">
                            <i class="fas fa-lightbulb text-amber-500 mr-1"></i>
                            Sebaiknya buat estimasi harga dulu di Kalkulator Harga, lalu pilih dari tab "Dari Kalkulator Harga".
                        </p>
                        <a href="{{ route('admin.price-calculator.create') }}" target="_blank" class="w-full block text-center admin-btn-sm admin-btn-info mb-3">
                            <i class="fas fa-calculator mr-1"></i> Buka Kalkulator Harga
                        </a>
                        <hr>
                        <button type="submit" class="w-full admin-btn-sm admin-btn-primary">
                            <i class="fas fa-save mr-1"></i> Simpan Proposal
                        </button>
                        <a href="{{ route('admin.proposals.index') }}" class="block w-full text-center admin-btn-sm admin-btn-secondary">
                            Batal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function switchMethod(method) {
    document.querySelectorAll('.method-content').forEach(el => el.classList.add('hidden'));
    document.getElementById('method-' + method).classList.remove('hidden');
    document.querySelectorAll('.tab-method').forEach(el => {
        el.classList.remove('active');
        el.style.color = '#6b7280';
        el.style.borderBottomColor = 'transparent';
        el.style.fontWeight = '400';
    });
    const active = document.querySelector(`.tab-method[data-method="${method}"]`);
    active.classList.add('active');
    active.style.color = '#059669';
    active.style.borderBottomColor = '#059669';
    active.style.fontWeight = '600';
}
</script>
@endpush