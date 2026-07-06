@extends('layouts.frontend')

@section('title', __('messages.seo.contact_title'))
@section('meta_description', __('messages.seo.contact_desc'))
@section('og_title', __('messages.seo.contact_title'))
@section('og_description', __('messages.seo.contact_desc'))

@section('content')
    {{-- HERO --}}
    <section class="relative min-h-[65vh] flex items-center overflow-hidden">
        <img
            src="{{ asset('frontend/assets/img/contact-top.jpg') }}"
            alt="@lang('messages.nav.contact')"
            class="absolute inset-0 w-full h-full object-cover"
        >

        <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/55 to-black/40"></div>

        <div class="container-custom relative z-10">
            <div class="max-w-3xl">
                {{-- Breadcrumb --}}
                <div class="flex items-center gap-2 text-white/70 text-sm mb-6">
                    <a href="{{ route('homepage') }}" class="hover:text-white transition">
                        @lang('messages.nav.home')
                    </a>
                    <i class="bx bx-chevron-right"></i>
                    <span>@lang('messages.nav.contact')</span>
                </div>

                {{-- Badge --}}
                <span class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md border border-white/20 px-4 py-2 rounded-full text-secondary text-sm font-medium mb-5">
                    <i class="bx bx-message-rounded-dots"></i>
                    @lang('messages.contact.hero_subtitle')
                </span>

                {{-- Title --}}
                <h1 class="text-white font-bold text-4xl md:text-6xl leading-tight">
                    @lang('messages.nav.contact')
                </h1>

                {{-- Description --}}
                <p class="text-white/80 mt-6 text-base md:text-lg max-w-2xl">
                    @lang('messages.contact.hero_desc')
                </p>

                {{-- Stats --}}
                <div class="flex flex-wrap gap-4 mt-8">
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl px-5 py-4">
                        <h4 class="text-white text-2xl font-bold">
                            24/7
                        </h4>
                        <span class="text-white/70 text-sm">
                            @lang('messages.contact.whatsapp_support')
                        </span>
                    </div>

                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl px-5 py-4">
                        <h4 class="text-white text-2xl font-bold">
                            20+
                        </h4>
                        <span class="text-white/70 text-sm">
                            @lang('messages.contact.tourism_activities')
                        </span>
                    </div>

                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl px-5 py-4">
                        <h4 class="text-white text-2xl font-bold">
                            2004
                        </h4>
                        <span class="text-white/70 text-sm">
                            @lang('messages.about.founded_since')
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CONTACT SECTION --}}
    <section class="section-padding -mt-20 relative z-20">
        <div class="container-custom">
            <div class="grid lg:grid-cols-[420px_1fr] gap-8">

                {{-- LEFT INFO --}}
                <div class="space-y-5">

                    <div class="bg-white rounded-[32px] p-8 shadow-xl">
                        <span class="section-subtitle">
                            @lang('messages.contact.subtitle')
                        </span>

                        <h2 class="text-3xl font-bold text-primary-900 mb-4">
                            @lang('messages.contact.title')
                        </h2>

                        <p class="text-primary-600 leading-relaxed">
                            @lang('messages.contact.description')
                        </p>
                    </div>

                    {{-- PHONE --}}
                    <div class="bg-white rounded-3xl p-6 shadow-lg hover:-translate-y-1 transition">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-2xl bg-primary/10 flex items-center justify-center text-primary text-2xl">
                                <i class="bx bxs-phone-call"></i>
                            </div>

                            <div>
                                <h4 class="font-semibold text-primary-900">
                                    @lang('messages.contact.phone_label')
                                </h4>
                                <p class="text-primary-500">
                                    +62 813 2885 6252
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- WHATSAPP --}}
                    <div class="bg-white rounded-3xl p-6 shadow-lg hover:-translate-y-1 transition">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-2xl bg-green-100 flex items-center justify-center text-green-600 text-2xl">
                                    <i class="bx bxl-whatsapp"></i>
                                </div>

                                <div>
                                    <h4 class="font-semibold text-primary-900">
                                        @lang('messages.contact.whatsapp_label')
                                    </h4>
                                    <p class="text-primary-500">
                                        +62 813 2885 6252
                                    </p>
                                </div>
                            </div>

                            <a
                                href="https://api.whatsapp.com/send?phone=6281328856252&text={{ urlencode(__('messages.whatsapp.default_message')) }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="button button-gold"
                            >
                                @lang('messages.contact.chat')
                            </a>
                        </div>
                    </div>

                    {{-- EMAIL --}}
                    <div class="bg-white rounded-3xl p-6 shadow-lg hover:-translate-y-1 transition">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-2xl bg-primary/10 flex items-center justify-center text-primary text-2xl">
                                <i class="bx bx-envelope"></i>
                            </div>

                            <div>
                                <h4 class="font-semibold text-primary-900">
                                    @lang('messages.contact.email_label')
                                </h4>
                                <p class="text-primary-500">
                                    edpdewiga@gmail.com
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- INSTAGRAM --}}
                    <div class="bg-white rounded-3xl p-6 shadow-lg hover:-translate-y-1 transition">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-2xl bg-pink-100 flex items-center justify-center text-pink-600 text-2xl">
                                <i class="bx bxl-instagram"></i>
                            </div>

                            <div>
                                <h4 class="font-semibold text-primary-900">
                                    @lang('messages.contact.instagram_label')
                                </h4>
                                <p class="text-primary-500">
                                    @desawisatagabugan
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT FORM --}}
                <div class="bg-white rounded-[32px] shadow-xl p-8 md:p-10">
                    <div class="mb-8">
                        <span class="section-subtitle">
                            @lang('messages.contact.form_subtitle')
                        </span>

                        <h2 class="text-3xl font-bold text-primary-900">
                            @lang('messages.contact.form_title')
                        </h2>

                        <p class="text-primary-500 mt-2">
                            @lang('messages.contact.form_desc')
                        </p>
                    </div>

                    <form id="myForm" action="{{ route('send.email') }}" method="post">
                        @csrf

                        <div class="grid md:grid-cols-2 gap-5">

                            <div class="md:col-span-2">
                                <input
                                    type="text"
                                    name="name"
                                    required
                                    placeholder="@lang('messages.contact.form_name_placeholder')"
                                    class="w-full h-14 px-5 rounded-2xl shadow-lg focus:border-primary focus:ring-0"
                                >
                            </div>

                            <div class="md:col-span-2">
                                <input
                                    type="email"
                                    name="email"
                                    required
                                    placeholder="@lang('messages.contact.form_email_placeholder')"
                                    class="w-full h-14 px-5 rounded-2xl shadow-lg focus:border-primary focus:ring-0"
                                >
                            </div>

                            <div class="md:col-span-2">
                                <input
                                    type="text"
                                    name="subject"
                                    required
                                    placeholder="@lang('messages.contact.form_subject_placeholder')"
                                    class="w-full h-14 px-5 rounded-2xl shadow-lg focus:border-primary focus:ring-0"
                                >
                            </div>

                            <div class="md:col-span-2">
                                <textarea
                                    name="message"
                                    required
                                    rows="7"
                                    placeholder="@lang('messages.contact.form_message_placeholder')"
                                    class="w-full px-5 py-4 rounded-2xl shadow-lg focus:border-primary focus:ring-0 resize-none"
                                ></textarea>
                            </div>

                            <div class="md:col-span-2">
                                <button
                                    id="submitBtn"
                                    type="submit"
                                    class="button w-full justify-center text-base py-4"
                                >
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
        <div class="container-custom">
            <div class="overflow-hidden rounded-[32px] shadow-xl">
                <iframe
                    src="https://www.google.com/maps?q=Desa%20Wisata%20Gabugan&output=embed"
                    width="100%"
                    height="500"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy">
                </iframe>
            </div>
        </div>
    </section>
@endsection
