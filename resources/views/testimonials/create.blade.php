@extends('layouts.frontend')

@section('title', __('Submit Testimonial'))
@section('meta_description', __('Share your experience visiting Desa Wisata Gabugan'))

@section('content')
    {{-- Page Header --}}
    <section class="relative h-[40vh] min-h-[280px] overflow-hidden">
        <div class="absolute inset-0">
            <img src="{{ asset('frontend/assets/img/contact-hero.jpg') }}" alt="{{ __('Submit Testimonial') }}"
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/50 to-black/70"></div>
        </div>
        <div class="relative z-10 h-full flex flex-col items-center justify-center text-center px-4">
            <span class="text-secondary text-sm font-semibold uppercase tracking-[3px] mb-3">
                {{ __('Share Your Experience') }}
            </span>
            <h1 class="font-heading text-4xl md:text-5xl text-white font-bold max-w-2xl">
                {{ __('Submit Testimonial') }}
            </h1>
            <p class="text-stone-300 mt-3 max-w-lg text-sm md:text-base">
                {{ __('Tell us about your visit to Desa Wisata Gabugan. Your feedback helps others discover this wonderful place.') }}
            </p>
        </div>
    </section>

    {{-- Form Section --}}
    <section class="section-padding">
        <div class="container-custom max-w-2xl">
            {{-- Flash Messages --}}
            @if(session()->has('message'))
                <div class="px-5 py-4 rounded-xl mb-6 text-sm font-medium
                    {{ session()->get('alert-type') === 'success' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-700 border border-red-200' }}">
                    <div class="flex items-center gap-3">
                        <i class="bx {{ session()->get('alert-type') === 'success' ? 'bxs-check-circle' : 'bxs-error-circle' }} text-lg"></i>
                        <span>{{ session()->get('message') }}</span>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-stone-100">
                    <div class="w-12 h-12 rounded-full bg-secondary/10 text-secondary flex items-center justify-center text-xl">
                        <i class="bx bxs-star"></i>
                    </div>
                    <div>
                        <h2 class="font-heading text-xl font-semibold text-primary-900">{{ __('Your Testimonial') }}</h2>
                        <p class="text-sm text-primary-400">{{ __('Fields marked with * are required') }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('testimonials.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    {{-- Name --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-primary-700 mb-1.5">
                            {{ __('Your Name') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                               class="w-full px-4 py-3 rounded-xl shadow-md text-sm
                                      focus:ring-2 focus:ring-secondary/30 focus:border-secondary
                                      @error('name') border-red-300 bg-red-50 @enderror"
                               placeholder="{{ __('e.g. John Doe') }}" required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Language Selector --}}
                    <div>
                        <label class="block text-sm font-medium text-primary-700 mb-1.5">
                            {{ __('Language') }} <span class="text-red-500">*</span>
                        </label>
                        <div class="flex gap-3">
                            <label class="flex items-center gap-2 px-4 py-3 rounded-xl shadow-md cursor-pointer
                                         has-[:checked]:border-secondary has-[:checked]:bg-secondary/5
                                         transition-all duration-200 flex-1">
                                <input type="radio" name="locale" value="id"
                                       class="w-4 h-4 text-secondary focus:ring-secondary/30"
                                       @checked(old('locale', 'id') === 'id')>
                                <div>
                                    <span class="text-sm font-medium text-primary-800">Bahasa Indonesia</span>
                                    <p class="text-xs text-primary-400">{{ __('Write in Indonesian') }}</p>
                                </div>
                            </label>
                            <label class="flex items-center gap-2 px-4 py-3 rounded-xl shadow-md cursor-pointer
                                         has-[:checked]:border-secondary has-[:checked]:bg-secondary/5
                                         transition-all duration-200 flex-1">
                                <input type="radio" name="locale" value="en"
                                       class="w-4 h-4 text-secondary focus:ring-secondary/30"
                                       @checked(old('locale') === 'en')>
                                <div>
                                    <span class="text-sm font-medium text-primary-800">English</span>
                                    <p class="text-xs text-primary-400">{{ __('Write in English') }}</p>
                                </div>
                            </label>
                        </div>
                        @error('locale')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Content --}}
                    <div>
                        <label for="content" class="block text-sm font-medium text-primary-700 mb-1.5">
                            {{ __('Your Experience') }} <span class="text-red-500">*</span>
                        </label>
                        <textarea id="content" name="content" rows="5"
                                  class="w-full px-4 py-3 rounded-xl shadow-md text-sm resize-y
                                         focus:ring-2 focus:ring-secondary/30 focus:border-secondary
                                         @error('content') border-red-300 bg-red-50 @enderror"
                                  placeholder="{{ __('Share your story... What did you enjoy most about your visit?') }}"
                                  required>{{ old('content') }}</textarea>
                        @error('content')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Avatar (optional) --}}
                    <div>
                        <label for="avatar" class="block text-sm font-medium text-primary-700 mb-1.5">
                            {{ __('Your Photo') }} <span class="text-primary-300 font-normal">({{ __('optional') }})</span>
                        </label>
                        <div class="flex items-center gap-4">
                            <div id="avatarPreview" class="w-16 h-16 rounded-full bg-stone-100 flex items-center justify-center text-stone-400 text-2xl overflow-hidden flex-shrink-0">
                                <i class="bx bx-user"></i>
                            </div>
                            <div class="flex-1">
                                <input type="file" id="avatar" name="avatar"
                                       class="w-full text-sm file:mr-4 file:py-2 file:px-4
                                              file:rounded-lg file:border-0 file:text-sm file:font-medium
                                              file:bg-secondary/10 file:text-secondary
                                              hover:file:bg-secondary/20 cursor-pointer
                                              @error('avatar') border-red-300 @enderror"
                                       accept="image/jpeg,image/png">
                                <p class="text-xs text-primary-300 mt-1">{{ __('Accepted formats: JPG, PNG. Max 2MB.') }}</p>
                            </div>
                        </div>
                        @error('avatar')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <div class="pt-2">
                        <button type="submit"
                                class="w-full sm:w-auto px-8 py-3 bg-secondary hover:bg-secondary/90 text-primary-900 font-semibold
                                       rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl
                                       flex items-center justify-center gap-2">
                            <i class="bx bxs-send"></i>
                            {{ __('Submit Testimonial') }}
                        </button>
                    </div>
                </form>
            </div>

            {{-- Back Link --}}
            <div class="text-center mt-6">
                <a href="{{ route('homepage') }}" class="text-sm text-primary-400 hover:text-secondary transition-colors">
                    <i class="bx bx-arrow-back align-middle"></i>
                    {{ __('Back to Homepage') }}
                </a>
            </div>
        </div>
    </section>
@endsection

@push('script-alt')
<script>
    // Preview avatar image before upload
    document.getElementById('avatar').addEventListener('change', function(e) {
        const preview = document.getElementById('avatarPreview');
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                preview.innerHTML = '<img src="' + event.target.result + '" class="w-full h-full object-cover">';
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
