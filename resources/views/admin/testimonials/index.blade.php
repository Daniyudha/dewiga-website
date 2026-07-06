@extends('layouts.app')

@section('title', 'Testimonials - Admin Dewiga')

@section('content')
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">{{ __('Testimonials') }}</h1>
            <p class="text-sm text-gray-500 mt-1">Manage visitor testimonials</p>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="admin-card">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Content ID') }}</th>
                        <th>{{ __('Content EN') }}</th>
                        <th>{{ __('Lang') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Date') }}</th>
                        <th class="text-center">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($testimonials as $testimonial)
                        <tr>
                            <td class="font-medium text-gray-900">{{ $loop->iteration }}</td>
                            <td>
                                <div class="flex items-center gap-3">
                                    @if($testimonial->avatar && file_exists(public_path('storage/' . $testimonial->avatar)))
                                        <img src="{{ asset('storage/' . $testimonial->avatar) }}" alt="{{ $testimonial->name }}"
                                             class="w-9 h-9 rounded-full object-cover border border-gray-200">
                                    @else
                                        <img src="https://i.pravatar.cc/36?u={{ urlencode($testimonial->name) }}"
                                             alt="{{ $testimonial->name }}"
                                             class="w-9 h-9 rounded-full object-cover border border-gray-200">
                                    @endif
                                    <span class="font-medium">{{ $testimonial->name }}</span>
                                </div>
                            </td>
                            <td class="max-w-[200px]">
                                <p class="text-sm text-gray-600 line-clamp-2">{{ Str::limit($testimonial->content_id ?: $testimonial->content, 80) }}</p>
                            </td>
                            <td class="max-w-[200px]">
                                <p class="text-sm text-gray-600 line-clamp-2">{{ Str::limit($testimonial->content_en ?: $testimonial->content, 80) }}</p>
                            </td>
                            <td>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $testimonial->locale === 'id' ? 'bg-red-50 text-red-700' : 'bg-blue-50 text-blue-700' }}">
                                    {{ strtoupper($testimonial->locale) }}
                                </span>
                            </td>
                            <td>
                                @if($testimonial->is_active)
                                    <span class="admin-badge-green">{{ __('Active') }}</span>
                                @else
                                    <span class="admin-badge-gray">{{ __('Inactive') }}</span>
                                @endif
                            </td>
                            <td class="text-sm text-gray-500">
                                {{ $testimonial->created_at->format('d M Y') }}
                            </td>
                            <td>
                                <div class="flex items-center justify-center gap-2">
                                    {{-- Toggle Active/Inactive --}}
                                    <form method="POST" action="{{ route('admin.testimonials.toggle', [$testimonial]) }}" class="inline">
                                        @csrf
                                        @method('patch')
                                        <button type="submit"
                                                class="admin-btn-sm {{ $testimonial->is_active ? 'admin-btn-warning' : 'admin-btn-success' }}">
                                            <i class="fas {{ $testimonial->is_active ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                            {{ $testimonial->is_active ? __('Hide') : __('Show') }}
                                        </button>
                                    </form>

                                    {{-- Delete --}}
                                    <form method="POST" action="{{ route('admin.testimonials.destroy', [$testimonial]) }}" class="inline">
                                        @csrf
                                        @method('delete')
                                        <button type="button" onclick="showDeleteModal(this.closest('form'))" class="admin-btn-danger admin-btn-sm">
                                            <i class="fas fa-trash"></i>
                                            {{ __('Delete') }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-8 text-gray-500">
                                <i class="fas fa-inbox text-3xl text-gray-300 block mb-2"></i>
                                {{ __('No testimonials yet.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
