@extends('layouts.frontend')

@section('title', __('messages.seo.contact_title'))
@section('meta_description', __('messages.seo.contact_desc'))
@section('og_title', __('messages.seo.contact_title'))
@section('og_description', __('messages.seo.contact_desc'))

@section('content')
    {{-- HERO --}}
    <section class="relative bg-neutral-900 overflow-hidden min-h-[60vh] flex items-center pt-24">
        <div class="absolute inset-0 z-0">
            @if($heroSetting && $heroSetting->slides->count() > 0)
                <img src="{{ $heroSetting->slides->first()->image_url }}" alt="@lang('messages.nav.contact')" class="w-full h-full object-cover">
            @elseif($heroSetting && $heroSetting->image_url)
                <img src="{{ $heroSetting->image_url }}" alt="@lang('messages.nav.contact')" class="w-full h-full object-cover">
            @else
                <img src="{{ asset('frontend/assets/img/contact-top.jpg') }}" alt="@lang('messages.nav.contact')" class="w-full h-full object-cover">
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-emerald-900/90 via-emerald-950/60 to-black/70 z-10"></div>
        </div>
        <div class="container mx-auto px-6 relative z-10 text-center text-white mt-8">
            <div class="inline-flex items-center gap-2 bg-[#053d2c]/80 border border-[#00a877]/30 px-4 py-1.5 rounded-full text-xs font-semibold tracking-wider text-[#00c887] uppercase mb-5 mx-auto">
                <i class="bx bx-message-rounded-dots"></i>
                @lang('messages.contact.hero_subtitle')
            </div>
            <h1 class="font-serif text-4xl md:text-6xl lg:text-7xl font-bold leading-tight mb-6 text-white">
                @lang('messages.nav.contact')
            </h1>
            <p class="text-neutral-200 text-base md:text-lg max-w-3xl mx-auto leading-relaxed font-light">
                @lang('messages.contact.hero_desc')
            </p>
        </div>
        <div class="absolute bottom-0 left-0 right-0 z-10 overflow-hidden">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="relative block w-full h-[30px] md:h-[50px] text-white fill-current">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V0C26.9,8.75,57.05,18.3,88.42,26.49,158.41,44.75,243.62,56.88,321.39,56.44Z"></path>
            </svg>
        </div>
    </section>

    {{-- CONTACT SECTION --}}
    <section class="py-24 -mt-10 relative z-20">
        <div class="container mx-auto px-6">
            <div class="grid lg:grid-cols-[420px_1fr] gap-8">

                {{-- LEFT INFO --}}
                <div class="space-y-5">
                    <div class="bg-white rounded-[2rem] p-8 shadow-xl">
                        <span class="text-[#00a877] font-semibold text-xs uppercase tracking-wider block mb-3">@lang('messages.contact.subtitle')</span>
                        <h2 class="font-serif text-3xl font-bold text-[#053d2c] mb-4">@lang('messages.contact.title')</h2>
                        <p class="text-neutral-600 leading-relaxed">@lang('messages.contact.description')</p>
                    </div>

                    <div class="bg-white rounded-3xl p-6 shadow-lg border border-neutral-100 hover:-translate-y-1 transition duration-300">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-2xl bg-[#00a877]/10 flex items-center justify-center text-[#00a877] text-2xl"><i class="bx bxs-phone-call"></i></div>
                            <div>
                                <h4 class="font-semibold text-[#053d2c]">@lang('messages.contact.phone_label')</h4>
                                <p class="text-neutral-500">+62 813 2885 6252</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-3xl p-6 shadow-lg border border-neutral-100 hover:-translate-y-1 transition duration-300">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-2xl bg-green-100 flex items-center justify-center text-green-600 text-2xl"><i class="bx bxl-whatsapp"></i></div>
                                <div>
                                    <h4 class="font-semibold text-[#053d2c]">@lang('messages.contact.whatsapp_label')</h4>
                                    <p class="text-neutral-500">+62 813 2885 6252</p>
                                </div>
                            </div>
                            <a href="https://api.whatsapp.com/send?phone=6281328856252&text={{ urlencode(__('messages.whatsapp.default_message')) }}" target="_blank" rel="noopener noreferrer"
                               class="inline-flex items-center gap-2 bg-[#00a877] hover:bg-[#009065] text-white px-5 py-2.5 rounded-full text-sm font-medium transition duration-300">
                                @lang('messages.contact.chat')
                            </a>
                        </div>
                    </div>

                    <div class="bg-white rounded-3xl p-6 shadow-lg border border-neutral-100 hover:-translate-y-1 transition duration-300">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-2xl bg-[#00a877]/10 flex items-center justify-center text-[#00a877] text-2xl"><i class="bx bx-envelope"></i></div>
                            <div>
                                <h4 class="font-semibold text-[#053d2c]">@lang('messages.contact.email_label')</h4>
                                <p class="text-neutral-500">edpdewiga@gmail.com</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-3xl p-6 shadow-lg border border-neutral-100 hover:-translate-y-1 transition duration-300">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-2xl bg-pink-100 flex items-center justify-center text-pink-600 text-2xl"><i class="bx bxl-instagram"></i></div>
                            <div>
                                <h4 class="font-semibold text-[#053d2c]">@lang('messages.contact.instagram_label')</h4>
                                <p class="text-neutral-500">@desawisatagabugan</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT FORM --}}
                <div class="bg-white rounded-[2rem] shadow-xl p-8 md:p-10">
                    <div class="mb-8">
                        <span class="text-[#00a877] font-semibold text-xs uppercase tracking-wider block mb-3">@lang('messages.contact.form_subtitle')</span>
                        <h2 class="font-serif text-3xl font-bold text-[#053d2c]">@lang('messages.contact.form_title')</h2>
                        <p class="text-neutral-500 mt-2">@lang('messages.contact.form_desc')</p>
                    </div>

                    <form id="myForm" action="{{ route('send.email') }}" method="post">
                        @csrf
                        <div class="grid md:grid-cols-2 gap-5">
                            <div class="md:col-span-2">
                                <input type="text" name="name" required placeholder="@lang('messages.contact.form_name_placeholder')"
                                       class="w-full h-14 px-5 shadow-md rounded-2xl border border-neutral-200 focus:border-[#00a877] focus:ring-0 transition">
                            </div>
                            <div class="md:col-span-2">
                                <input type="email" name="email" required placeholder="@lang('messages.contact.form_email_placeholder')"
                                       class="w-full h-14 px-5 shadow-md rounded-2xl border border-neutral-200 focus:border-[#00a877] focus:ring-0 transition">
                            </div>
                            <div class="md:col-span-2">
                                <input type="text" name="subject" required placeholder="@lang('messages.contact.form_subject_placeholder')"
                                       class="w-full h-14 px-5 shadow-md rounded-2xl border border-neutral-200 focus:border-[#00a877] focus:ring-0 transition">
                            </div>
                            <div class="md:col-span-2">
                                <textarea name="message" required rows="7" placeholder="@lang('messages.contact.form_message_placeholder')"
                                          class="w-full px-5 py-4 shadow-md rounded-2xl border border-neutral-200 focus:border-[#00a877] focus:ring-0 resize-none transition"></textarea>
                            </div>
                            <div class="md:col-span-2">
                                <button id="submitBtn" type="submit"
                                        class="w-full inline-flex items-center justify-center gap-2 bg-[#00a877] hover:bg-[#009065] text-white px-8 py-4 rounded-full font-medium transition duration-300 text-base">
                                    <i class="bx bx-send"></i>
                                    @lang('messages.contact.form_submit')
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </section>

    {{-- MAP --}}
    <section class="pb-24">
        <div class="container mx-auto px-6">
            <div class="overflow-hidden rounded-[2rem] shadow-xl">
                <iframe src="https://www.google.com/maps?q=Desa%20Wisata%20Gabugan&output=embed" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </section>

    {{-- FAQ (PERTANYAAN YANG SERING DIAJUKAN) --}}
    @include('partials.home-faq')
@endsection