@extends('layouts.app')

@section('title', 'Assign Activities - Admin Dewiga')

@section('content')
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">{{ __('Assign Activities') }}</h1>
            <p class="text-sm text-gray-500 mt-1">
                Pilih kegiatan yang akan muncul untuk jadwal <strong>{{ $schedule->event_name }}</strong>. Kegiatan yang tidak dipilih tidak akan ditampilkan di peta event.
            </p>
        </div>
        <a href="{{ route('admin.schedules.index') }}" class="admin-btn-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('Back') }}
        </a>
    </div>

    <div class="admin-card">
        <div class="admin-card-body">
            <form method="POST" action="{{ route('admin.schedules.store-assign-activities', $schedule) }}" class="space-y-6">
                @csrf

                <div class="admin-card bg-gray-50 p-4 rounded-xl border border-gray-200 mb-6">
                    <h3 class="font-heading font-semibold text-gray-800 mb-1">{{ __('Kegiatan Tersedia') }}</h3>
                    <p class="text-sm text-gray-600 mb-3">{{ __('Centang kegiatan yang akan diaktifkan untuk jadwal ini.') }}</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @forelse($activities as $activity)
                        <div class="admin-form-group flex items-start gap-3 p-3 border border-gray-200 rounded-xl bg-white {{ $assigned->contains($activity->id) ? 'border-primary-200 bg-primary-50' : '' }}">
                            <input type="checkbox" name="activities[]" value="{{ $activity->id }}"
                                {{ $assigned->contains($activity->id) ? 'checked' : '' }}
                                class="admin-form-input w-5 h-5 mt-1">
                            <div>
                                <div class="font-medium text-gray-800">{{ $activity->title_id }}</div>
                                <div class="text-xs text-gray-500">{{ $activity->duration_id }}</div>
                                @if($assigned->contains($activity->id))
                                    <span class="inline-block mt-1 px-2 py-0.5 text-xs font-semibold bg-primary-100 text-primary-700 rounded">{{ __('Active') }}</span>
                                @endif
                            </div>
                        </div>
                        @empty
                        <div class="col-span-full text-center py-4 text-gray-500">{{ __('No active activities found.') }}</div>
                        @endforelse
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="admin-btn-success"><i class="fas fa-save"></i> {{ __('Save Assignment') }}</button>
                    <a href="{{ route('admin.schedules.index') }}" class="admin-btn-secondary">{{ __('Cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
@endsection
