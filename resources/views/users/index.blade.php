@extends('layouts.app')

@section('title', 'Users - Admin Dewiga')

@section('content')
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">{{ __('Users') }}</h1>
            <p class="text-sm text-gray-500 mt-1">Manage registered users and their roles</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="admin-btn-primary">
            <i class="fas fa-plus"></i> {{ __('Add User') }}
        </a>
    </div>

    <div class="admin-card">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>{{ __('User') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('Roles') }}</th>
                        <th>{{ __('Created') }}</th>
                        <th class="text-center">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <img src="https://i.pravatar.cc/32?u={{ urlencode($user->email) }}"
                                         alt="{{ $user->name }}"
                                         class="w-8 h-8 rounded-full object-cover border border-gray-200 flex-shrink-0">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $user->name }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="text-sm text-gray-600">{{ $user->email }}</td>
                            <td>
                                <div class="flex flex-wrap gap-1">
                                    @forelse($user->roles as $role)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                            {{ $role->slug === 'super_admin' ? 'bg-red-100 text-red-700' : ($role->slug === 'admin' ? 'bg-green-100 text-green-700' : ($role->slug === 'finance' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700')) }}">
                                            {{ $role->name }}
                                        </span>
                                    @empty
                                        <span class="text-xs text-gray-400">-</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="text-sm text-gray-500">{{ $user->created_at->format('d M Y') }}</td>
                            <td class="text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 hover:text-blue-800 text-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($user->id !== Auth::id())
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                          onclick="showDeleteModal(this.closest('form'))" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-gray-500">
                                <i class="fas fa-inbox text-3xl text-gray-300 block mb-2"></i>
                                {{ __('No users found.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="admin-card-footer">
                {{ $users->links("vendor.pagination.tailwind") }}
            </div>
        @endif
    </div>
@endsection