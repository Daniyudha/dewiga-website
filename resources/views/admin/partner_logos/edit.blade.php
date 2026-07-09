@extends('layouts.app')

@section('title', 'Edit Partner Logo - Admin Dewiga')

@section('content')
    {{-- Page Header --}}
    <div class="mb-6">
        <a href="{{ route('admin.partner_logos.index') }}" class="text-sm text-gray-500 hover:text-primary-600 transition-colors mb-2 inline-block">
            <i class="fas fa-arrow-left mr-1"></i>
            {{ __('Back to Partner Logos') }}
        </a>
        <h1 class="text-2xl font-heading font-bold text-gray-900">{{ __('Edit Partner Logo') }}</h1>
        <p class="text-sm text-gray-500 mt-1">Update partner logo information</p>
    </div>

    {{-- Form Card --}}
    <div class="admin-card max-w-2xl">
        <div class="admin-card-body">
            <form method="POST" action="{{ route('admin.partner_logos.update', [$partnerLogo]) }}" enctype="multipart/form-data">
                @csrf
                @method('put')

                {{-- Current Logo Preview --}}
                @if($partnerLogo->image)
                <div class="mb-5 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <p class="text-xs font-medium text-gray-500 mb-2">{{ __('Current Logo') }}</p>
                    <img src="{{ asset('storage/' . $partnerLogo->image) }}"
                         alt="{{ $partnerLogo->name }}"
                         class="h-16 w-auto object-contain">
                </div>
                @endif

                {{-- Name --}}
                <div class="admin-form-group mb-5">
                    <label for="name" class="admin-form-label">{{ __('Name') }} <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $partnerLogo->name) }}"
                           class="admin-form-input @error('name') error @enderror"
                           placeholder="e.g. Ministry of Tourism" required>
                    @error('name')
                        <p class="admin-form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Image --}}
                <div class="admin-form-group mb-5">
                    <label for="image" class="admin-form-label">{{ __('Change Logo Image') }}</label>
                    <input type="file" id="image" name="image" accept="image/*"
                           class="admin-form-input @error('image') error @enderror p-2">
                    <p class="text-xs text-gray-400 mt-1">Leave empty to keep current logo. Recommended: PNG/SVG. Max 2MB.</p>
                    @error('image')
                        <p class="admin-form-error">{{ $message }}</p>
                    @enderror
                    <div id="image-preview" class="mt-2 hidden">
                        <img src="" alt="Preview" class="h-16 w-auto object-contain border border-gray-200 rounded-lg p-2 bg-gray-50">
                    </div>
                </div>

                {{-- URL --}}
                <div class="admin-form-group mb-5">
                    <label for="url" class="admin-form-label">{{ __('Website URL') }}</label>
                    <input type="url" id="url" name="url" value="{{ old('url', $partnerLogo->url) }}"
                           class="admin-form-input @error('url') error @enderror"
                           placeholder="https://example.com">
                    @error('url')
                        <p class="admin-form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4 mb-5">
                    {{-- Order --}}
                    <div class="admin-form-group">
                        <label for="order" class="admin-form-label">{{ __('Order') }}</label>
                        <input type="number" id="order" name="order" value="{{ old('order', $partnerLogo->order) }}"
                               class="admin-form-input @error('order') error @enderror"
                               placeholder="0" min="0">
                        @error('order')
                            <p class="admin-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Active --}}
                    <div class="admin-form-group">
                        <label class="admin-form-label">{{ __('Status') }}</label>
                        <div class="flex items-center gap-3 mt-2">
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="is_active" value="1"
                                       {{ old('is_active', $partnerLogo->is_active) ? 'checked' : '' }}
                                       class="w-4 h-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                <span class="text-sm text-gray-700">{{ __('Active') }}</span>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                    <button type="submit" class="admin-btn-primary">
                        <i class="fas fa-save"></i>
                        {{ __('Update Logo') }}
                    </button>
                    <a href="{{ route('admin.partner_logos.index') }}" class="admin-btn-secondary">
                        {{ __('Cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.getElementById('image').addEventListener('change', function(e) {
    const preview = document.getElementById('image-preview');
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.querySelector('img').src = e.target.result;
            preview.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
});
</script>
@endpush
