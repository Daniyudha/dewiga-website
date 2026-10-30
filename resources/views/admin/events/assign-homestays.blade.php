@extends('layouts.app')

@section('title', 'Assign Homestays - Admin Dewiga')

@section('content')
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">{{ __('Assign Homestays') }}</h1>
            <p class="text-sm text-gray-500 mt-1">
                Pilih homestay yang digunakan untuk event <strong>{{ $event->name_id }}</strong> dan tentukan nama kelompok yang menempati tiap homestay.
            </p>
        </div>
        <a href="{{ route('admin.events.edit', $event) }}" class="admin-btn-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('Back') }}
        </a>
    </div>

    <div class="admin-card">
        <div class="admin-card-body">
            <form method="POST" action="{{ route('admin.events.store-assign-homestays', $event) }}" class="space-y-6">
                @csrf

                <div class="admin-card bg-gray-50 p-4 rounded-xl border border-gray-200 mb-6">
                    <h3 class="font-heading font-semibold text-gray-800 mb-1">{{ __('Homestay Tersedia') }}</h3>
                    <p class="text-sm text-gray-600 mb-3">{{ __('Centang homestay yang akan diaktifkan untuk event ini.') }}</p>

                    <div class="overflow-x-auto">
                        <table class="admin-table w-full text-sm">
                            <thead>
                                <tr>
                                    <th class="w-10 !text-center">{{ __('Select') }}</th>
                                    <th>{{ __('Homestay') }}</th>
                                    <th>{{ __('Address') }}</th>
                                    <th class="!text-center">{{ __('Capacity') }}</th>
                                    <th>{{ __('Group Name') }} (Indonesia)</th>
                                    <th>{{ __('Group Name') }} (English)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($homestays as $homestay)
                                <tr>
                                    <td class="!text-center">
                                        <input type="checkbox" name="homestays[]" value="{{ $homestay->id }}"
                                            {{ $assigned->has($homestay->id) ? 'checked' : '' }}
                                            class="admin-form-input">
                                    </td>
                                    <td class="font-medium">{{ $homestay->name_id }}</td>
                                    <td class="text-gray-600 max-w-[160px] truncate">{{ $homestay->address_id }}</td>
                                    <td class="!text-center">{{ $homestay->capacity }}</td>
                                    <td>
                                        <input type="text" name="group_name_id[{{ $homestay->id }}]"
                                            value="{{ old('group_name_id.' . $homestay->id, $assigned->get($homestay->id)) }}"
                                            class="admin-form-input w-full" placeholder="{{ __('Nama kelompok') }}">
                                    </td>
                                    <td>
                                        <input type="text" name="group_name_en[{{ $homestay->id }}]"
                                            value="{{ old('group_name_en.' . $homestay->id) }}"
                                            class="admin-form-input w-full" placeholder="{{ __('Group name') }}">
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-gray-500">{{ __('No active homestays found.') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="admin-btn-success"><i class="fas fa-save"></i> {{ __('Save Assignment') }}</button>
                    <a href="{{ route('admin.schedules.index', ['tab' => 'peta-event']) }}" class="admin-btn-secondary">{{ __('Cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
@endsection
