@extends('layouts.app')

@section('title', 'Edit Proposal: ' . ($proposal->proposal_title ?? $proposal->institution_name))

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-heading font-bold text-gray-900">
        {{ isset($duplicate) ? 'Duplikasi Proposal' : 'Edit Proposal' }}
    </h1>
    <a href="{{ route('admin.proposals.show', $proposal) }}" class="admin-btn-sm admin-btn-secondary">
        <i class="fas fa-arrow-left mr-1"></i> Kembali
    </a>
</div>

<form method="POST" action="{{ isset($duplicate) ? route('admin.proposals.store') : route('admin.proposals.update', $proposal) }}" id="proposalForm">
    @csrf
    @if(!isset($duplicate)) @method('PUT') @endif

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
                            <label class="form-label">Judul Proposal</label>
                            <input type="text" name="proposal_title" value="{{ old('proposal_title', $proposal->proposal_title ?? '') }}" class="form-input" placeholder="Program Kunjungan Edukasi...">
                        </div>
                        <div>
                            <label class="form-label">Template Rundown</label>
                            <select name="rundown_template_id" class="form-input">
                                <option value="">-- Tanpa Template --</option>
                                @foreach($templates as $t)
                                    <option value="{{ $t->id }}" {{ old('rundown_template_id', $proposal->rundown_template_id) == $t->id ? 'selected' : '' }}>{{ $t->name }} ({{ $t->duration_days }}H)</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Tujuan Program</label>
                        <textarea name="program_objective" rows="2" class="form-input" placeholder="Tujuan dari program...">{{ old('program_objective', $proposal->program_objective) }}</textarea>
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
                <div class="admin-card-body space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Nama Sekolah / Instansi <span class="text-red-500">*</span></label>
                            <input type="text" name="institution_name" value="{{ old('institution_name', $proposal->institution_name) }}" class="form-input" required>
                        </div>
                        <div>
                            <label class="form-label">Penanggung Jawab <span class="text-red-500">*</span></label>
                            <input type="text" name="contact_person" value="{{ old('contact_person', $proposal->contact_person) }}" class="form-input" required>
                        </div>
                        <div>
                            <label class="form-label">No. WhatsApp <span class="text-red-500">*</span></label>
                            <input type="text" name="whatsapp" value="{{ old('whatsapp', $proposal->whatsapp) }}" class="form-input" required>
                        </div>
                        <div>
                            <label class="form-label">Tanggal Kedatangan <span class="text-red-500">*</span></label>
                            <input type="date" name="arrival_date" value="{{ old('arrival_date', $proposal->arrival_date->format('Y-m-d')) }}" class="form-input" required>
                        </div>
                        <div>
                            <label class="form-label">Tanggal Kepulangan <span class="text-red-500">*</span></label>
                            <input type="date" name="departure_date" value="{{ old('departure_date', $proposal->departure_date->format('Y-m-d')) }}" class="form-input" required>
                        </div>
                        <div>
                            <label class="form-label">Jumlah Siswa <span class="text-red-500">*</span></label>
                            <input type="number" name="student_count" id="student_count" value="{{ old('student_count', $proposal->student_count) }}" class="form-input" min="0" required>
                        </div>
                        <div>
                            <label class="form-label">Jumlah Pendamping</label>
                            <input type="number" name="companion_count" id="companion_count" value="{{ old('companion_count', $proposal->companion_count) }}" class="form-input" min="0">
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" rows="2" class="form-input">{{ old('notes', $proposal->notes) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Paket Program --}}
            <div class="admin-card shadow-md">
                <div class="admin-card-header">
                    <h3 class="font-heading font-semibold text-gray-800">
                        <i class="fas fa-calculator text-primary-600 mr-2"></i> Paket Program
                    </h3>
                </div>
                <div class="admin-card-body space-y-4">
                    @php
                        $liveInNights = $proposal->items->where('item_code', 'live_in')->first()->frequency ?? 0;
                        $mealCount = $proposal->items->where('item_code', 'meal')->first()->frequency ?? 0;
                        $regularActivity = $proposal->items->where('item_code', 'regular_activity')->first()->frequency ?? 0;
                        $artSessions = $proposal->items->where('item_code', 'participant_art_activity')->first()->frequency ?? 0;
                        $culturalPerformances = $proposal->items->where('item_code', 'cultural_performance')->first()->frequency ?? 0;
                        $snackCount = $proposal->items->where('item_code', 'snack')->first()->frequency ?? 0;
                    @endphp
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="form-label">Live In (malam)</label>
                            <input type="number" name="live_in_nights" id="live_in_nights" value="{{ old('live_in_nights', $liveInNights) }}" class="form-input" min="0">
                        </div>
                        <div>
                            <label class="form-label">Makan (kali)</label>
                            <input type="number" name="meal_count" id="meal_count" value="{{ old('meal_count', $mealCount) }}" class="form-input" min="0">
                        </div>
                        <div>
                            <label class="form-label">Snack (kali)</label>
                            <input type="number" name="snack_count" id="snack_count" value="{{ old('snack_count', $snackCount) }}" class="form-input" min="0">
                        </div>
                        <div>
                            <label class="form-label">Kegiatan Reguler</label>
                            <input type="number" name="regular_activity_count" id="regular_activity_count" value="{{ old('regular_activity_count', $regularActivity) }}" class="form-input" min="0">
                        </div>
                        <div>
                            <label class="form-label">Sesi Kesenian</label>
                            <input type="number" name="art_sessions" id="art_sessions" value="{{ old('art_sessions', $artSessions) }}" class="form-input" min="0">
                        </div>
                        <div>
                            <label class="form-label">Pertunjukan Kesenian</label>
                            <input type="number" name="cultural_performances" id="cultural_performances" value="{{ old('cultural_performances', $culturalPerformances) }}" class="form-input" min="0">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="form-label">Pembulatan</label>
                            <select name="rounding_type" class="form-input">
                                <option value="none" {{ $proposal->rounding_type == 'none' ? 'selected' : '' }}>Tanpa Pembulatan</option>
                                <option value="up_1000" {{ $proposal->rounding_type == 'up_1000' ? 'selected' : '' }}>Ke atas Rp1.000</option>
                                <option value="up_5000" {{ $proposal->rounding_type == 'up_5000' ? 'selected' : '' }}>Ke atas Rp5.000</option>
                                <option value="up_10000" {{ $proposal->rounding_type == 'up_10000' ? 'selected' : '' }}>Ke atas Rp10.000</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="admin-card shadow-md">
                <div class="admin-card-body space-y-3">
                    <button type="submit" class="w-full admin-btn-sm admin-btn-primary">
                        <i class="fas fa-save mr-1"></i> Simpan
                    </button>
                    <a href="{{ route('admin.proposals.show', $proposal) }}" class="block w-full text-center admin-btn-sm admin-btn-secondary">Batal</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection