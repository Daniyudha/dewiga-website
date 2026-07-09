@extends('layouts.app')

@section('title', 'Travel Packages - Admin Dewiga')

@section('content')
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">{{ __('Travel Packages') }}</h1>
            <p class="text-sm text-gray-500 mt-1">Manage gallery images for travel packages</p>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="admin-card">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('Type') }}</th>
                        <th>{{ __('Location') }}</th>
                        <th>{{ __('Price') }}</th>
                        <th class="!text-center">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($travel_packages as $item)
                        <tr>
                            <td class="font-medium text-gray-900">{{ $loop->iteration }}</td>
                            <td>
                                <span class="admin-badge-blue">{{ $item->type }}</span>
                            </td>
                            <td>{{ $item->location }}</td>
                            <td class="font-medium text-primary-700">{{ formatPrice($item->price) }}</td>
                            <td>
                                <div class="flex items-center justify-center">
                                    <a href="{{ route('admin.travel_packages.edit', [$item]) }}" class="text-primary-600 hover:text-primary-800 transition-colors">
                                        <i class="fas fa-images"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-gray-500">
                                <i class="fas fa-inbox text-3xl text-gray-300 block mb-2"></i>
                                {{ __('No travel packages found.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($travel_packages->hasPages())
            <div class="admin-card-footer">
                {{ $travel_packages->links("vendor.pagination.tailwind") }}
            </div>
        @endif
    </div>
@endsection
