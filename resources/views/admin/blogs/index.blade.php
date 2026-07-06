@extends('layouts.app')

@section('title', 'Blogs - Admin Dewiga')

@section('content')
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">{{ __('Blogs') }}</h1>
            <p class="text-sm text-gray-500 mt-1">Manage your blog articles</p>
        </div>
        <a href="{{ route('admin.blogs.create') }}" class="admin-btn-primary">
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
                        <th>{{ __('Title') }}</th>
                        <th>{{ __('Image') }}</th>
                        <th>{{ __('Excerpt') }}</th>
                        <th>{{ __('Category') }}</th>
                        <th class="text-center">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($blogs as $blog)
                        <tr>
                            <td class="font-medium text-gray-900">{{ $loop->iteration }}</td>
                            <td class="max-w-[200px] truncate font-medium">{{ $blog->title }}</td>
                            <td>
                                @if($blog->image && file_exists(public_path('storage/' . $blog->image)))
                                    <a href="{{ asset('storage/' . $blog->image) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}" class="admin-thumb">
                                    </a>
                                @else
                                    <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="max-w-[250px] truncate text-gray-500">{{ Str::limit($blog->excerpt, 60) }}</td>
                            <td>
                                @if($blog->category)
                                    <span class="admin-badge-green">{{ $blog->category->name }}</span>
                                @else
                                    <span class="admin-badge-gray">{{ __('No Category') }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.blogs.edit', [$blog]) }}" class="admin-btn-info admin-btn-sm">
                                        <i class="fas fa-edit"></i>
                                        {{ __('Edit') }}
                                    </a>
                                    <form method="POST" action="{{ route('admin.blogs.destroy', [$blog]) }}" class="inline">
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
                            <td colspan="6" class="text-center py-8 text-gray-500">
                                <i class="fas fa-inbox text-3xl text-gray-300 block mb-2"></i>
                                {{ __('No blogs found.') }}
                                <a href="{{ route('admin.blogs.create') }}" class="text-primary-600 hover:underline block mt-1">
                                    {{ __('Create your first blog') }}
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($blogs->hasPages())
            <div class="admin-card-footer">
                {{ $blogs->links() }}
            </div>
        @endif
    </div>
@endsection
