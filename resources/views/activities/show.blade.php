@extends('layouts.frontend')

@section('title', $activity->title . ' - Aktivitas Desa Wisata Gabugan')
@section('meta_description', strip_tags($activity->description))
@section('meta_keywords', 'aktivitas ' . $activity->title . ', desa wisata gabugan, sleman')

@section('content')
    {{-- HERO --}}
    <section class="relative bg-neutral-900 overflow-hidden min-h-[50vh] flex items-end pt-24">
        <div class="absolute inset-0 z-0">
            <img src="{{ $activity->image ? asset('storage/' . $activity->image) : asset('frontend/assets/img/hero2.jpg') }}" alt="{{ $activity->title }}" class="w-full h-full object-cover opacity-40">
            <div class="absolute inset-0 bg-gradient-to-t from-emerald-900/90 via-emerald-950/60 to-black/60 z-10"></div>
        </div>
        <div class="relative z-10 container mx-auto px-6 pb-16">
            <div class="max-w-3xl">
                <div class="flex flex-wrap gap-2 mb-4">
                    @if($activity->duration)
                    <span class="inline-flex items-center gap-1 text-xs bg-white/20 text-white px-3 py-1 rounded-full backdrop-blur-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        {{ $activity->duration }}
                    </span>
                    @endif
                    @if($activity->min_pax)
                    <span class="inline-flex items-center gap-1 text-xs bg-white/20 text-white px-3 py-1 rounded-full backdrop-blur-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        Min. {{ $activity->min_pax }}
                    </span>
                    @endif
                </div>
                <h1 class="font-serif text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-4 text-white">{{ $activity->title }}</h1>
                <p class="text-neutral-300 text-base max-w-2xl leading-relaxed">{{ $activity->description }}</p>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 z-10 overflow-hidden">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="relative block w-full h-[30px] md:h-[50px] text-white fill-current">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V0C26.9,8.75,57.05,18.3,88.42,26.49,158.41,44.75,243.62,56.88,321.39,56.44Z"></path>
            </svg>
        </div>
    </section>

    {{-- CONTENT --}}
    <section class="py-16 bg-white">
        <div class="container mx-auto px-6 max-w-4xl">
            <div class="grid md:grid-cols-3 gap-8">
                {{-- Main Info --}}
                <div class="md:col-span-2">
                    <div class="prose prose-lg max-w-none">
                        <h2 class="font-serif text-2xl font-bold text-[#053d2c]">@lang('messages.activities.detail_title')</h2>
                        <p class="text-neutral-700 leading-relaxed">{{ $activity->description }}</p>
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="space-y-6">
                    <div class="bg-[#f8fdfb] rounded-2xl p-6 border border-neutral-100">
                        <h3 class="font-bold text-[#053d2c] text-lg mb-4">@lang('messages.activities.sidebar_duration')</h3>

                        <div class="space-y-3 text-sm">
                            @if($activity->duration)
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 bg-[#e8fbf3] text-[#00a877] rounded-lg flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </span>
                                <div>
                                    <p class="text-xs text-neutral-500">@lang('messages.activities.sidebar_duration')</p>
                                    <p class="font-semibold text-[#053d2c]">{{ $activity->duration }}</p>
                                </div>
                            </div>
                            @endif
                            @if($activity->min_pax)
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 bg-[#e8fbf3] text-[#00a877] rounded-lg flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                </span>
                                <div>
                                    <p class="text-xs text-neutral-500">@lang('messages.activities.sidebar_min_pax')</p>
                                    <p class="font-semibold text-[#053d2c]">{{ $activity->min_pax }}</p>
                                </div>
                            </div>
                            @endif
                            @if($activity->includes)
                            <div class="flex items-start gap-3">
                                <span class="w-8 h-8 bg-[#e8fbf3] text-[#00a877] rounded-lg flex items-center justify-center shrink-0 mt-0.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </span>
                                <div>
                                    <p class="text-xs text-neutral-500">@lang('messages.activities.sidebar_includes')</p>
                                    <p class="font-semibold text-[#053d2c] text-sm">{{ $activity->includes }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <a href="https://api.whatsapp.com/send?phone=6281328856252&text={{ urlencode('Halo, saya tertarik dengan aktivitas: ' . $activity->title . ' di Desa Wisata Gabugan') }}"
                       target="_blank"
                       class="block w-full text-center bg-[#00a877] hover:bg-[#009065] text-white py-3.5 rounded-2xl font-semibold text-sm transition-all duration-300 shadow-sm hover:shadow-md">
                        <i class="bx bxl-whatsapp mr-2"></i>@lang('messages.activities.btn_whatsapp')
                    </a>

                    <a href="{{ route('activities.index') }}" class="block w-full text-center bg-white border border-neutral-200 text-neutral-600 hover:text-[#00a877] hover:border-[#00a877] py-3 rounded-2xl font-medium text-sm transition-all">
                        @lang('messages.activities.btn_back')
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- RELATED --}}
    @php $related = \App\Models\Activity::where('id', '!=', $activity->id)->where('is_featured', true)->inRandomOrder()->limit(3)->get(); @endphp
    @if($related->count() > 0)
    <section class="py-16 bg-[#f8fdfb]">
        <div class="container mx-auto px-6">
            <h2 class="font-serif text-2xl font-bold text-[#053d2c] mb-8 text-center">@lang('messages.activities.related_title')</h2>
            <div class="grid md:grid-cols-3 gap-6">
                @foreach($related as $rel)
                <a href="{{ route('activities.show', $rel->slug) }}" class="bg-white rounded-2xl overflow-hidden border border-neutral-100 shadow-sm hover:shadow-lg transition group">
                    <div class="h-40 overflow-hidden">
                        <img src="{{ $rel->image ? asset('storage/' . $rel->image) : asset('frontend/assets/img/hero2.jpg') }}" alt="{{ $rel->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-[#053d2c] text-sm group-hover:text-[#00a877] transition-colors">{{ $rel->title }}</h3>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif
@endsection
