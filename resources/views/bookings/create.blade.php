@extends('layouts.frontend')

@section('title', $travel_package->title ?? $travel_package->location . ' - Booking')
@section('meta_description', 'Book ' . ($travel_package->title ?? $travel_package->location) . ' - ' . strip_tags($travel_package->description))

@push('style-alt')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css"/>
<style>
.total-display {
    background: linear-gradient(135deg, #053d2c, #00a877);
    color: white;
    padding: 1rem;
    border-radius: 1rem;
    text-align: center;
}
.total-amount {
    font-size: 2rem;
    font-weight: bold;
}
</style>
@endpush

@section('content')
    {{-- HERO --}}
    <section class="relative bg-neutral-900 overflow-hidden min-h-[60vh] flex items-end pt-24">
        <div class="absolute inset-0 z-0">
            @php
                $heroImg = $travel_package->galleries->count() > 0 && $travel_package->galleries->first()->images
                    ? asset('storage/' . $travel_package->galleries->first()->images)
                    : asset('frontend/assets/img/package-top.jpg');
            @endphp
            <img src="{{ $heroImg }}" alt="{{ $travel_package->location }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-emerald-900/90 via-emerald-950/60 to-black/70 z-10"></div>
        </div>
        <div class="relative z-10 container mx-auto px-6 pb-16">
            <div class="max-w-4xl">
                <div class="flex items-center gap-2 mb-6">
                    @if($travel_package->is_signature)
                    <span class="inline-flex items-center gap-1 bg-gradient-to-r from-amber-500 to-orange-500 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                        <i class="bx bxs-star"></i> Signature
                    </span>
                    @endif
                    <span class="inline-flex items-center gap-2 bg-[#00a877] text-white px-5 py-2 rounded-full text-sm font-semibold">{{ $travel_package->type }}</span>
                </div>
                <h1 class="font-serif text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-6 text-white">{{ $travel_package->title ?? $travel_package->location }}</h1>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 z-10 overflow-hidden">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="relative block w-full h-[30px] md:h-[50px] text-white fill-current">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V0C26.9,8.75,57.05,18.3,88.42,26.49,158.41,44.75,243.62,56.88,321.39,56.44Z"></path>
            </svg>
        </div>
    </section>

    {{-- BREADCRUMB --}}
    <section class="bg-white border-b border-neutral-100">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center gap-2 text-sm text-neutral-500">
                <a href="{{ route('homepage') }}" class="hover:text-[#00a877]">@lang('messages.nav.home')</a>
                <i class="bx bx-chevron-right"></i>
                <a href="{{ route('travel_package.index') }}" class="hover:text-[#00a877]">@lang('messages.nav.packages')</a>
                <i class="bx bx-chevron-right"></i>
                <a href="{{ route('travel_package.show', $travel_package->slug) }}" class="hover:text-[#00a877]">{{ $travel_package->title ?? $travel_package->type }}</a>
                <i class="bx bx-chevron-right"></i>
                <span class="text-[#053d2c] font-medium">@lang('messages.booking.title')</span>
            </div>
        </div>
    </section>

    {{-- BOOKING FORM --}}
    <section class="py-24 bg-[#f8fdfb]">
        <div class="container mx-auto px-6">
            <div class="max-w-2xl mx-auto">
                <div class="bg-white rounded-[2rem] shadow-xl p-8 md:p-10">
                    <h2 class="font-serif text-3xl font-bold text-[#053d2c] mb-2">@lang('messages.booking.title')</h2>
                    <p class="text-neutral-500 mb-6">{{ $travel_package->title ?? $travel_package->location }}</p>

                    @if(session('message'))
                    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-4">
                        <i class="bx bx-check-circle mr-2"></i>
                        {{ session('message') }}
                    </div>
                    @endif

                    <form method="POST" action="{{ route('booking.store') }}" class="space-y-4" id="bookingForm">
                        @csrf
                        <input type="hidden" name="travel_package_id" value="{{ $travel_package->id }}">

                        <div class="total-display">
                            <p class="text-sm opacity-80 mb-1">@lang('Total Price')</p>
                            <p class="total-amount" id="totalAmount">{{ formatPrice($travel_package->price) }}</p>
                            <p class="text-xs opacity-70" id="totalPeople">1 @lang('person')</p>
                        </div>

                        <div>
                            <label class="block text-xs text-neutral-500 mb-1 font-medium">@lang('Your Name') <span class="text-red-500">*</span></label>
                            <input type="text" name="name" placeholder="{{ __('John Doe') }}" required
                                class="w-full px-4 py-3 shadow-md rounded-xl border border-neutral-200 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                        </div>

                        <div>
                            <label class="block text-xs text-neutral-500 mb-1 font-medium">@lang('Institution/Organization')</label>
                            <input type="text" name="institution" placeholder="{{ __('Company/Organization Name') }}"
                                class="w-full px-4 py-3 shadow-md rounded-xl border border-neutral-200 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                        </div>

                        <div>
                            <label class="block text-xs text-neutral-500 mb-1 font-medium">@lang('Your Email') <span class="text-red-500">*</span></label>
                            <input type="email" name="email" placeholder="{{ __('your@email.com') }}" required
                                class="w-full px-4 py-3 shadow-md rounded-xl border border-neutral-200 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                        </div>

                        <div>
                            <label class="block text-xs text-neutral-500 mb-1 font-medium">@lang('Your Phone Number') <span class="text-red-500">*</span></label>
                            <input type="tel" name="number_phone" placeholder="{{ __('081234567890') }}" required
                                class="w-full px-4 py-3 shadow-md rounded-xl border border-neutral-200 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                        </div>

                        <div>
                            <label class="block text-xs text-neutral-500 mb-1 font-medium">@lang('Number of People')</label>
                            <input type="number" name="people_count" id="peopleCount" value="1" min="1"
                                class="w-full px-4 py-3 shadow-md rounded-xl border border-neutral-200 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition"
                                onchange="updateTotal()">
                        </div>

                        <div>
                            <label class="block text-xs text-neutral-500 mb-1 font-medium">@lang('Start Date') <span class="text-red-500">*</span></label>
                            <input type="date" name="start_date" required
                                class="w-full px-4 py-3 shadow-md rounded-xl border border-neutral-200 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                        </div>

                        <div>
                            <label class="block text-xs text-neutral-500 mb-1 font-medium">@lang('End Date')</label>
                            <input type="date" name="end_date"
                                class="w-full px-4 py-3 shadow-md rounded-xl border border-neutral-200 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                        </div>

                        <div>
                            <label class="block text-xs text-neutral-500 mb-1 font-medium">@lang('Description/Notes')</label>
                            <textarea name="description" rows="3" placeholder="{{ __('Any special requests or notes...') }}"
                                class="w-full px-4 py-3 shadow-md rounded-xl border border-neutral-200 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition"></textarea>
                        </div>

                        <button type="submit"
                            class="w-full bg-[#00a877] hover:bg-[#009065] text-white px-8 py-4 rounded-xl font-medium transition duration-300">
                            <i class="bx bx-send mr-2"></i>
                            @lang('Send Booking')
                        </button>
                    </form>

                    <p class="text-xs text-neutral-500 mt-4 text-center">
                        <i class="bx bx-info-circle"></i>
                        {{ __('You will receive a confirmation email after submitting the form.') }}
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script-alt')
<script>
const pricePerPerson = {{ $travel_package->price ?? 0 }};

function formatRupiah(amount) {
    return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
}

function updateTotal() {
    const count = parseInt(document.getElementById('peopleCount').value) || 1;
    const total = pricePerPerson * count;
    document.getElementById('totalAmount').textContent = formatRupiah(total);
    const personText = count + ' {{ __('person') }}' + (count > 1 ? 's' : '');
    document.getElementById('totalPeople').textContent = personText;
}
</script>
@endpush
