@extends('layouts.app')

@section('title', 'Pengaturan Profil Desa')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-heading font-bold text-gray-900">Pengaturan Profil Desa</h1>
    <a href="{{ route('admin.proposals.index') }}" class="admin-btn-sm admin-btn-secondary">
        <i class="fas fa-arrow-left mr-1"></i> Kembali
    </a>
</div>

<form method="POST" action="{{ route('admin.proposal-settings.update') }}">
    @csrf @method('PUT')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Profil Desa --}}
        <div class="admin-card shadow-md">
            <div class="admin-card-header">
                <h3 class="font-heading font-semibold text-gray-800">
                    <i class="fas fa-info-circle mr-2 text-primary-600"></i> Profil Desa
                </h3>
            </div>
            <div class="admin-card-body space-y-5">
                <div>
                    <label class="form-label block text-sm font-medium text-gray-700 mb-1">Tagline</label>
                    <input type="text" name="tagline" value="{{ old('tagline', $settings->tagline) }}" class="form-input w-full" placeholder="Belajar, Berbudaya, Berkarakter">
                </div>
                <div>
                    <label class="form-label block text-sm font-medium text-gray-700 mb-1">Profil Singkat</label>
                    <textarea name="short_profile" rows="5" class="form-input w-full" placeholder="Deskripsi singkat tentang Desa Wisata Gabugan...">{{ old('short_profile', $settings->short_profile) }}</textarea>
                </div>
                <div>
                    <label class="form-label block text-sm font-medium text-gray-700 mb-1">Visi</label>
                    <textarea name="vision" rows="3" class="form-input w-full">{{ old('vision', $settings->vision) }}</textarea>
                </div>
                <div>
                    <label class="form-label block text-sm font-medium text-gray-700 mb-1">Misi</label>
                    <textarea name="mission" rows="4" class="form-input w-full">{{ old('mission', $settings->mission) }}</textarea>
                </div>
                <div>
                    <label class="form-label block text-sm font-medium text-gray-700 mb-1">Keunggulan</label>
                    <textarea name="advantages" rows="4" class="form-input w-full">{{ old('advantages', $settings->advantages) }}</textarea>
                </div>
                <div>
                    <label class="form-label block text-sm font-medium text-gray-700 mb-1">Komitmen</label>
                    <textarea name="commitment" rows="3" class="form-input w-full">{{ old('commitment', $settings->commitment) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Lokasi & Kontak --}}
        <div class="admin-card shadow-md">
            <div class="admin-card-header">
                <h3 class="font-heading font-semibold text-gray-800">
                    <i class="fas fa-map-marker-alt mr-2 text-primary-600"></i> Lokasi & Kontak
                </h3>
            </div>
            <div class="admin-card-body space-y-5">
                <div>
                    <label class="form-label block text-sm font-medium text-gray-700 mb-1">Alamat / Lokasi</label>
                    <textarea name="location" rows="3" class="form-input w-full">{{ old('location', $settings->location) }}</textarea>
                </div>
                <div>
                    <label class="form-label block text-sm font-medium text-gray-700 mb-1">Google Maps URL</label>
                    <input type="url" name="maps_url" value="{{ old('maps_url', $settings->maps_url) }}" class="form-input w-full" placeholder="https://maps.google.com/...">
                </div>
                <div>
                    <label class="form-label block text-sm font-medium text-gray-700 mb-1">Kontak</label>
                    <input type="text" name="contact" value="{{ old('contact', $settings->contact) }}" class="form-input w-full" placeholder="Telp/WA: 0812-xxxx-xxxx">
                </div>
            </div>

            {{-- Ketentuan Homestay --}}
            <div class="admin-card-header border-t border-gray-200 mt-0">
                <h3 class="font-heading font-semibold text-gray-800">
                    <i class="fas fa-home mr-2 text-primary-600"></i> Homestay
                </h3>
            </div>
            <div class="admin-card-body space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label block text-sm font-medium text-gray-700 mb-1">Check In</label>
                        <input type="time" name="check_in_time" value="{{ old('check_in_time', $settings->check_in_time) }}" class="form-input w-full">
                    </div>
                    <div>
                        <label class="form-label block text-sm font-medium text-gray-700 mb-1">Check Out</label>
                        <input type="time" name="check_out_time" value="{{ old('check_out_time', $settings->check_out_time) }}" class="form-input w-full">
                    </div>
                </div>
                <div>
                    <label class="form-label block text-sm font-medium text-gray-700 mb-1">Ketentuan Homestay</label>
                    <textarea name="homestay_terms" rows="4" class="form-input w-full">{{ old('homestay_terms', $settings->homestay_terms) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Ketentuan Program (full width row) --}}
        <div class="lg:col-span-2 admin-card shadow-md">
            <div class="admin-card-header">
                <h3 class="font-heading font-semibold text-gray-800">
                    <i class="fas fa-file-contract mr-2 text-primary-600"></i> Ketentuan Program
                </h3>
            </div>
            <div class="admin-card-body">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="space-y-5">
                        <div>
                            <label class="form-label block text-sm font-medium text-gray-700 mb-1">Uang Muka (DP)</label>
                            <textarea name="dp_terms" rows="3" class="form-input w-full">{{ old('dp_terms', $settings->dp_terms) }}</textarea>
                        </div>
                        <div>
                            <label class="form-label block text-sm font-medium text-gray-700 mb-1">Ketentuan Pembayaran</label>
                            <textarea name="payment_terms" rows="3" class="form-input w-full">{{ old('payment_terms', $settings->payment_terms) }}</textarea>
                        </div>
                        <div>
                            <label class="form-label block text-sm font-medium text-gray-700 mb-1">Ketentuan Pembatalan</label>
                            <textarea name="cancellation_terms" rows="3" class="form-input w-full">{{ old('cancellation_terms', $settings->cancellation_terms) }}</textarea>
                        </div>
                    </div>
                    <div class="space-y-5">
                        <div>
                            <label class="form-label block text-sm font-medium text-gray-700 mb-1">Perubahan Peserta</label>
                            <textarea name="participant_change_terms" rows="3" class="form-input w-full">{{ old('participant_change_terms', $settings->participant_change_terms) }}</textarea>
                        </div>
                        <div>
                            <label class="form-label block text-sm font-medium text-gray-700 mb-1">Force Majeure</label>
                            <textarea name="force_majeure_terms" rows="3" class="form-input w-full">{{ old('force_majeure_terms', $settings->force_majeure_terms) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tombol Simpan --}}
        <div class="lg:col-span-2">
            <button type="submit" class="w-full md:w-auto admin-btn-sm admin-btn-primary px-8 py-3">
                <i class="fas fa-save mr-2"></i> Simpan Pengaturan
            </button>
        </div>
    </div>
</form>
@endsection

<style>
    .form-input {
        border: 1px solid #d1d5db; /* Tailwind's gray-300 */
        border-radius: 0.375rem; /* Tailwind's rounded-md */
        padding: 0.5rem 0.75rem; /* Tailwind's py-2 px-3 */
        font-size: 0.875rem; /* Tailwind's text-sm */
        line-height: 1.25rem; /* Tailwind's leading-5 */
    }
</style>