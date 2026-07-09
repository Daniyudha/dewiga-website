@extends('layouts.frontend')

@section('title', 'Semua Aktivitas - Desa Wisata Gabugan')
@section('meta_description', 'Jelajahi berbagai aktivitas seru di Desa Wisata Gabugan, Sleman. Mulai dari membajak sawah, membatik, gamelan, river trekking, hingga kuliner khas.')
@section('meta_keywords', 'aktivitas desa wisata gabugan, kegiatan wisata sleman, wisata edukasi jogja, outbound, river trekking, batik, gamelan')

@section('content')
    {{-- HERO SECTION WITH WAVE --}}
    <section class="relative bg-neutral-900 overflow-hidden min-h-[60vh] flex items-center pt-24">
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('frontend/assets/img/hero2.jpg') }}" alt="Aktivitas Desa Wisata Gabugan"
                 class="w-full h-full object-cover opacity-30">
            <div class="absolute inset-0 bg-gradient-to-t from-emerald-950 via-emerald-950/40 to-black/40 z-10"></div>
        </div>
        <div class="container mx-auto px-6 relative z-10 text-center text-white">
            <div class="inline-flex items-center gap-2 bg-[#00a877]/90 backdrop-blur-md px-4 py-1.5 rounded-full text-xs font-semibold tracking-wider text-white uppercase mb-5 mx-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Eksplorasi Aktivitas
            </div>
            <h1 class="font-serif text-4xl md:text-6xl lg:text-7xl font-bold leading-tight mb-5 text-white">
                Semua Aktivitas Seru
            </h1>
            <p class="text-neutral-200 text-base md:text-lg max-w-2xl mx-auto leading-relaxed font-light">
                Dari membajak sawah dengan kerbau hingga belajar gamelan Jawa — temukan pengalaman tak terlupakan di Desa Wisata Gabugan
            </p>
        </div>
        <div class="absolute bottom-0 left-0 right-0 z-10 overflow-hidden">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="relative block w-full h-[30px] md:h-[50px] text-[#f8fdfb] fill-current">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V0C26.9,8.75,57.05,18.3,88.42,26.49,158.41,44.75,243.62,56.88,321.39,56.44Z"></path>
            </svg>
        </div>
    </section>

    {{-- Activities Grid --}}
    <section class="pb-24 bg-[#f8fdfb]">
        <div class="container mx-auto px-6">
            @if($activities && $activities->count() > 0)
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($activities as $act)
                <div class="bg-white rounded-[2rem] overflow-hidden border border-neutral-100 shadow-sm hover:shadow-xl transition-all duration-300 group flex flex-col">
                    <a href="{{ route('activities.show', $act->slug) }}" class="block relative h-52 overflow-hidden shrink-0">
                        <img src="{{ $act->image ? asset('storage/' . $act->image) : asset('frontend/assets/img/hero2.jpg') }}" alt="{{ $act->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent"></div>
                        <div class="absolute top-4 right-4 w-9 h-9 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center text-[#053d2c] opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                        </div>
                    </a>
                    <div class="p-6 flex flex-col flex-1">
                        <a href="{{ route('activities.show', $act->slug) }}">
                            <h3 class="font-serif text-lg font-bold text-[#053d2c] mb-2 group-hover:text-[#00a877] transition-colors line-clamp-2">{{ $act->title }}</h3>
                        </a>
                        <p class="text-neutral-600 text-sm leading-relaxed mb-4 line-clamp-3">{{ $act->description }}</p>

                        <div class="flex flex-wrap gap-2 mb-4">
                            @if($act->duration)
                            <span class="inline-flex items-center gap-1 text-[11px] bg-[#e8fbf3] text-[#00a877] px-2.5 py-1 rounded-full font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                {{ $act->duration }}
                            </span>
                            @endif
                            @if($act->min_pax)
                            <span class="inline-flex items-center gap-1 text-[11px] bg-[#e8fbf3] text-[#00a877] px-2.5 py-1 rounded-full font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                Min. {{ $act->min_pax }}
                            </span>
                            @endif
                        </div>

                        <div class="mt-auto flex items-center justify-end gap-2 pt-4 border-t border-neutral-100">
                            <a href="{{ route('activities.show', $act->slug) }}" class="text-[#00a877] text-sm font-semibold hover:gap-2 transition-all inline-flex items-center gap-1">
                                Lihat Detail
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-16">
                <p class="text-neutral-500">Belum ada aktivitas tersedia.</p>
            </div>
            @endif
        </div>
    </section>
@endsection
</write_to_file>