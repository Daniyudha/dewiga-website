@extends('layouts.app')

@section('title', 'Hero Settings - Admin Dewiga')

@section('content')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-heading font-bold text-gray-900">{{ __('Hero Settings') }}</h1>
        <p class="text-sm text-gray-500 mt-1">Manage hero banners for each page</p>
    </div>
</div>

{{-- Hero List --}}
<div class="admin-card">
    <div class="admin-card-header">
        <h3 class="font-heading font-semibold text-gray-800">All Page Heroes</h3>
        <span class="text-xs text-gray-500">{{ $heroes->count() }} pages</span>
    </div>
    <div class="admin-card-body p-0">
        <div class="overflow-x-auto">
            <table class="admin-table w-full">
                <thead>
                    <tr class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-3">Page</th>
                        <th class="px-6 py-3">Images</th>
                        <th class="px-6 py-3">Last Updated</th>
                        <th class="px-6 py-3 !text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($heroes as $hero)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-primary-50 flex items-center justify-center text-primary-600 shrink-0">
                                    @switch($hero->page)
                                        @case('home') <i class="fas fa-home"></i> @break
                                        @case('about') <i class="fas fa-info-circle"></i> @break
                                        @case('contact') <i class="fas fa-envelope"></i> @break
                                        @case('gallery') <i class="fas fa-camera"></i> @break
                                        @case('homestay') <i class="fas fa-building"></i> @break
                                        @case('impact') <i class="fas fa-globe-asia"></i> @break
                                        @case('blog') <i class="fas fa-blog"></i> @break
                                        @case('packages') <i class="fas fa-box"></i> @break
                                        @case('activities') <i class="fas fa-running"></i> @break
                                        @default <i class="fas fa-image"></i>
                                    @endswitch
                                </div>
                                <div>
                                    <span class="font-medium text-gray-900 capitalize">{{ $hero->page }}</span>
                                    @if($hero->page === 'home')
                                        <span class="ml-2 px-2 py-0.5 bg-amber-100 text-amber-700 text-[10px] font-semibold rounded-full">Multi-slide</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-medium text-gray-700">{{ $hero->slides->count() }}</span>
                                <span class="text-xs text-gray-400">images</span>
                                @if($hero->page === 'home')
                                    <span class="text-xs text-gray-400">/ 5 max</span>
                                @else
                                    <span class="text-xs text-gray-400">/ 1 max</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-500">{{ $hero->updated_at->diffForHumans() }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('admin.hero-settings.edit', [$hero]) }}" class="inline-flex items-center text-blue-600 font-medium rounded-lg hover:text-blue-800 transition">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
