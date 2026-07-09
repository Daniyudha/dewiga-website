@extends('layouts.app')

@section('title', 'Categories - Admin Dewiga')

@section('content')
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">{{ __('Categories') }}</h1>
            <p class="text-sm text-gray-500 mt-1">Manage blog categories</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="admin-btn-primary">
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
                        <th>{{ __('Name') }}</th>
                        <th class="!text-center">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td class="font-medium text-gray-900">{{ $loop->iteration }}</td>
                            <td>
                                <span class="admin-badge-green">{{ $category->name }}</span>
                            </td>
                            <td>
                                <div class="flex items-center justify-center gap-4">
                                    <a href="{{ route('admin.categories.edit', [$category]) }}" class="text-blue-600 hover:text-blue-800 transition-colors">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.categories.destroy', [$category]) }}" class="inline">
                                        @csrf
                                        @method('delete')
                                        <button type="button" onclick="showDeleteModal(this.closest('form'))" class="text-red-600 hover:text-red-800 transition-colors">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-8 text-gray-500">
                                <i class="fas fa-inbox text-3xl text-gray-300 block mb-2"></i>
                                {{ __('No categories found.') }}
                                <a href="{{ route('admin.categories.create') }}" class="text-primary-600 hover:underline block mt-1">
                                    {{ __('Create your first category') }}
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
