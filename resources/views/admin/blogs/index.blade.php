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

    {{-- Status Filter --}}
    <div class="admin-card mb-6">
        <div class="admin-card-body">
            <form method="GET" action="{{ route('admin.blogs.index') }}" class="flex flex-wrap items-center gap-3">
                <label class="text-sm font-medium text-gray-700">{{ __('Status') }}:</label>
                <select name="status" class="admin-input w-auto min-w-[150px]" onchange="this.form.submit()">
                    <option value="">{{ __('All Status') }}</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>{{ __('Published') }}</option>
                    <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>{{ __('Scheduled') }}</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>{{ __('Draft') }}</option>
                </select>

                @if(request('status'))
                    <a href="{{ route('admin.blogs.index') }}" class="admin-btn-sm admin-btn-secondary text-red-600">
                        <i class="fas fa-times"></i> {{ __('Clear Filter') }}
                    </a>
                @endif
            </form>
        </div>
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
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('Author') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th class="!text-center">{{ __('Action') }}</th>
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
                            <td class="whitespace-nowrap text-gray-600">
                                {{ $blog->created_at->format('d M Y') }}
                            </td>
                            <td class="whitespace-nowrap">
                                @if($blog->user)
                                    <div class="flex items-center gap-2">
                                        <img src="https://i.pravatar.cc/24?u={{ urlencode($blog->user->email) }}"
                                             alt="{{ $blog->user->name }}"
                                             class="w-6 h-6 rounded-full object-cover border border-gray-200 flex-shrink-0">
                                        <span class="text-sm text-gray-700">{{ $blog->user->name }}</span>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $blog->status_badge }}">
                                    {{ $blog->status_label }}
                                </span>
                                @if($blog->status === 'scheduled' && $blog->published_at)
                                    <div class="text-[11px] text-gray-400 mt-1">
                                        {{ $blog->published_at->format('d M Y H:i') }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center justify-center gap-4">
                                    <a href="{{ route('admin.blogs.edit', [$blog]) }}" class="text-blue-600 hover:text-blue-800 transition-colors">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.blogs.destroy', [$blog]) }}" class="inline">
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
                            <td colspan="9" class="text-center py-8 text-gray-500">
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
                {{ $blogs->links('vendor.pagination.tailwind') }}
            </div>
        @endif
    </div>
@endsection
