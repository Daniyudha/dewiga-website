@extends('layouts.frontend')

@section('title', __('Submit Testimonial'))
@section('meta_description', __('Share your experience visiting Desa Wisata Gabugan'))

@section('content')
    {{-- HERO WITH WAVE --}}
    <section class="relative bg-neutral-900 overflow-hidden min-h-[50vh] flex items-center pt-8">
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('frontend/assets/img/contact-hero.jpg') }}" alt="{{ __('Submit Testimonial') }}"
                 class="w-full h-full object-cover opacity-25">
            <div class="absolute inset-0 bg-gradient-to-t from-emerald-950 via-emerald-950/50 to-black/50 z-10"></div>
        </div>
        <div class="container mx-auto px-6 relative z-10 text-center text-white">
            <div class="w-16 h-16 bg-[#00a877]/20 rounded-3xl flex items-center justify-center mx-auto mb-5 border border-white/10">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-[#00c887]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                </svg>
            </div>
            <h1 class="font-serif text-3xl md:text-5xl font-bold text-white mb-3">{{ __('Submit Testimonial') }}</h1>
            <p class="text-neutral-300 text-sm md:text-base max-w-lg mx-auto leading-relaxed font-light">
                Bagikan pengalaman seru Anda selama berkunjung ke Desa Wisata Gabugan
            </p>
        </div>
        {{-- Wave Separator --}}
        <div class="absolute bottom-0 left-0 right-0 z-10 overflow-hidden">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="relative block w-full h-[30px] md:h-[50px] text-[#f8fdfb] fill-current">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V0C26.9,8.75,57.05,18.3,88.42,26.49,158.41,44.75,243.62,56.88,321.39,56.44Z"></path>
            </svg>
        </div>
    </section>

    {{-- FORM --}}
    <section class="py-16 bg-[#f8fdfb]">
        <div class="container mx-auto px-6 max-w-lg">

            {{-- Flash --}}
            @if(session()->has('message'))
                <div class="px-4 py-3 rounded-2xl mb-6 text-sm font-medium flex items-center gap-2.5
                    {{ session()->get('alert-type') === 'success' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200' }}">
                    <i class="bx {{ session()->get('alert-type') === 'success' ? 'bxs-check-circle' : 'bxs-error-circle' }} text-base shrink-0"></i>
                    <span>{{ session()->get('message') }}</span>
                </div>
            @endif

            {{-- Card --}}
            <div class="bg-white rounded-3xl shadow-sm border border-neutral-100 overflow-hidden">
                
                {{-- Card Header --}}
                <div class="bg-gradient-to-r from-[#053d2c] to-[#0a5a3e] px-6 py-5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#00c887]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="font-serif font-bold text-white text-lg">{{ __('Tulis Testimoni') }}</h2>
                            <p class="text-[#00c887] text-xs">Bagikan cerita Anda kepada kami</p>
                        </div>
                    </div>
                </div>

                {{-- Card Body --}}
                <div class="px-6 py-6">
                    <form method="POST" action="{{ route('testimonials.store') }}" enctype="multipart/form-data">
                        @csrf

                        {{-- Name --}}
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Nama <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                </div>
                                <input type="text" id="name" name="name" value="{{ old('name') }}"
                                       class="w-full pl-10 pr-4 py-2.5 shadow-md text-sm rounded-xl
                                              focus:outline-none focus:ring-2 focus:ring-[#00a877]/20 focus:border-[#00a877]
                                              placeholder:text-gray-400 transition
                                              @error('name') border-red-300 bg-red-50 @enderror"
                                       placeholder="Masukkan nama Anda" required>
                            </div>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Language --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Bahasa <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-2.5">
                                <label class="flex items-center gap-2.5 px-3.5 py-3 rounded-xl border border-gray-200 cursor-pointer
                                             has-[:checked]:border-[#00a877] has-[:checked]:bg-[#e8fbf3] transition-all">
                                    <input type="radio" name="locale" value="id"
                                           class="w-4 h-4 text-[#00a877] focus:ring-[#00a877]/30"
                                           @checked(old('locale', 'id') === 'id')>
                                    <div>
                                        <span class="text-sm font-semibold text-[#053d2c]">🇮🇩 Indonesia</span>
                                        <p class="text-[11px] text-gray-400">Bahasa</p>
                                    </div>
                                </label>
                                <label class="flex items-center gap-2.5 px-3.5 py-3 rounded-xl border border-gray-200 cursor-pointer
                                             has-[:checked]:border-[#00a877] has-[:checked]:bg-[#e8fbf3] transition-all">
                                    <input type="radio" name="locale" value="en"
                                           class="w-4 h-4 text-[#00a877] focus:ring-[#00a877]/30"
                                           @checked(old('locale') === 'en')>
                                    <div>
                                        <span class="text-sm font-semibold text-[#053d2c]">🇬🇧 English</span>
                                        <p class="text-[11px] text-gray-400">Language</p>
                                    </div>
                                </label>
                            </div>
                            @error('locale')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Content --}}
                        <div class="mb-4">
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Pengalaman Anda <span class="text-red-500">*</span>
                            </label>
                            <textarea id="content" name="content" rows="4"
                                      class="w-full px-4 py-2.5 text-sm shadow-md rounded-xl resize-none
                                             focus:outline-none focus:ring-2 focus:ring-[#00a877]/20 focus:border-[#00a877]
                                             placeholder:text-gray-400 transition
                                             @error('content') border-red-300 bg-red-50 @enderror"
                                      placeholder="Ceritakan pengalaman seru Anda selama di Desa Wisata Gabugan... Kegiatan apa yang paling berkesan? Bagaimana pelayanan pemandu?">{{ old('content') }}</textarea>
                            @error('content')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Avatar --}}
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Foto <span class="text-xs text-gray-400 font-normal">(opsional)</span>
                            </label>
                            <div class="flex items-center gap-4">
                                <div id="avatarPreview" class="w-14 h-14 rounded-full bg-[#e8fbf3] flex items-center justify-center text-[#00a877] text-xl overflow-hidden shrink-0 border-2 border-dashed border-gray-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                </div>
                                <div>
                                    <label for="avatar" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-medium text-gray-600 bg-white border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 hover:border-gray-300 transition-all">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                        Pilih Foto
                                    </label>
                                    <input type="file" id="avatar" name="avatar" class="hidden" accept="image/jpeg,image/png">
                                    <p class="text-[11px] text-gray-400 mt-1">Format JPG/PNG, max 2MB</p>
                                </div>
                            </div>
                            @error('avatar')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Submit --}}
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 bg-[#00a877] hover:bg-[#009065] text-white py-3 rounded-2xl font-semibold text-sm transition-all duration-300 shadow-sm hover:shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            {{ __('Kirim Testimoni') }}
                        </button>

                        {{-- Back --}}
                        <div class="text-center mt-4">
                            <a href="{{ route('homepage') }}" class="text-xs text-gray-400 hover:text-[#00a877] transition-colors inline-flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Kembali ke Beranda
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script-alt')
<script>
    document.getElementById('avatar').addEventListener('change', function(e) {
        const preview = document.getElementById('avatarPreview');
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                preview.innerHTML = '<img src="' + event.target.result + '" class="w-full h-full object-cover rounded-full border-0">';
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
</write_to_file>