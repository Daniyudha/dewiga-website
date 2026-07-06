@extends('layouts.app')

@section('title', 'Travel Packages - Admin Dewiga')

@section('content')
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">{{ __('Travel Packages') }}</h1>
            <p class="text-sm text-gray-500 mt-1">Manage your travel package listings</p>
        </div>
        <a href="{{ route('admin.travel_packages.create') }}" class="admin-btn-primary">
            <i class="fas fa-plus"></i>
            {{ __('Add New') }}
        </a>
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
                        <th class="text-center">{{ __('Action') }}</th>
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
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.travel_packages.edit', [$item]) }}" class="admin-btn-info admin-btn-sm">
                                        <i class="fas fa-edit"></i>
                                        {{ __('Edit') }}
                                    </a>
                                    <form method="POST" action="{{ route('admin.travel_packages.destroy', [$item]) }}" class="inline">
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
                            <td colspan="5" class="text-center py-8 text-gray-500">
                                <i class="fas fa-inbox text-3xl text-gray-300 block mb-2"></i>
                                {{ __('No travel packages found.') }}
                                <a href="{{ route('admin.travel_packages.create') }}" class="text-primary-600 hover:underline block mt-1">
                                    {{ __('Create your first package') }}
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($travel_packages->hasPages())
            <div class="admin-card-footer">
                {{ $travel_packages->links() }}
            </div>
        @endif
    </div>
@endsection
