@extends('layouts.frontend')

@section('title', __('messages.seo.schedules_title'))
@section('meta_description', __('messages.seo.schedules_desc'))
@section('og_title', __('messages.seo.schedules_title'))
@section('og_description', __('messages.seo.schedules_desc'))
@section('twitter_title', __('messages.seo.schedules_title'))
@section('twitter_description', __('messages.seo.schedules_desc'))

@push('style-alt')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css">
<style>
.fc { font-family: inherit; }
.fc .fc-toolbar { margin-bottom: 1.5rem; }
.fc .fc-toolbar-title { font-size: 1.5rem; font-weight: 600; color: #053d2c; }
.fc .fc-button { background: #00a877 !important; border: none !important; text-transform: capitalize !important; }
.fc .fc-button:hover { background: #059669 !important; }
.fc .fc-daygrid-day { transition: background-color 0.2s; }
.fc .fc-daygrid-day:hover { background: #f9fafb; }
.fc-event { cursor: pointer; border-radius: 6px !important; padding: 2px 4px !important; }
.fc-event-title { font-weight: 600; font-size: 0.8rem; }
.fc .fc-daygrid-day.fc-day-today { background: #f0fdf4; }

/* Modal */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.5);
    z-index: 9999;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 1rem;
}
.modal-overlay.active { display: flex; }
.modal-box {
    background: white;
    border-radius: 1rem;
    max-width: 480px;
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
    animation: modalIn 0.2s ease-out;
}
@keyframes modalIn {
    from { opacity: 0; transform: scale(0.95) translateY(10px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}
.modal-header {
    background: linear-gradient(135deg, #053d2c, #00a877);
    padding: 1.5rem;
    border-radius: 1rem 1rem 0 0;
    position: relative;
}
.modal-close-btn {
    position: absolute;
    top: 0.75rem;
    right: 0.75rem;
    width: 32px;
    height: 32px;
    background: rgba(255,255,255,0.2);
    border: none;
    border-radius: 50%;
    color: white;
    font-size: 1.2rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}
.modal-close-btn:hover { background: rgba(255,255,255,0.35); }
.modal-body { padding: 1.5rem; }
.modal-body .row {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f0f0f0;
}
.modal-body .row:last-child { border-bottom: none; }
.modal-icon {
    width: 36px;
    height: 36px;
    background: #f0fdf4;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    color: #00a877;
}
.modal-label {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #9ca3af;
    font-weight: 600;
}
.modal-value {
    font-size: 0.95rem;
    color: #1f2937;
    font-weight: 500;
}

/* Type badges */
.type-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.125rem 0.625rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}
.type-open_trip { background: #dbeafe; color: #1e40af; }
.type-confirmed { background: #d1fae5; color: #065f46; }
.type-pending { background: #fef3c7; color: #92400e; }

/* Tab buttons */
.type-tabs {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-bottom: 1.5rem;
}
.type-tab {
    padding: 0.5rem 1.25rem;
    border-radius: 9999px;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    border: 2px solid transparent;
    background: #f3f4f6;
    color: #6b7280;
}
.type-tab:hover { background: #e5e7eb; }
.type-tab.active {
    background: #053d2c;
    color: white;
    border-color: #00a877;
}
.type-tab .count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 1.25rem;
    height: 1.25rem;
    border-radius: 9999px;
    font-size: 0.7rem;
    padding: 0 0.35rem;
    margin-left: 0.375rem;
}
.type-tab.active .count { background: rgba(255,255,255,0.2); color: white; }
.type-tab:not(.active) .count { background: #e5e7eb; color: #6b7280; }

/* Custom hidden class for JS toggling (avoid conflict with Tailwind) */
.is-hidden {
    display: none !important;
}
</style>
@endpush

@section('content')
    {{-- HERO --}}
    <section class="relative bg-neutral-900 overflow-hidden min-h-[60vh] flex items-center pt-24">
        <div class="absolute inset-0 z-0">
            @if($heroSetting && $heroSetting->slides->count() > 0)
                <img src="{{ $heroSetting->slides->first()->image_url }}" alt="@lang('messages.nav.schedules')" class="w-full h-full object-cover">
            @elseif($heroSetting && $heroSetting->image_url)
                <img src="{{ $heroSetting->image_url }}" alt="@lang('messages.nav.schedules')" class="w-full h-full object-cover">
            @else
                <img src="{{ asset('frontend/assets/img/hero2.jpg') }}" alt="@lang('messages.nav.schedules')" class="w-full h-full object-cover">
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-emerald-900/90 via-emerald-950/60 to-black/70 z-10"></div>
        </div>
        <div class="container mx-auto px-6 relative z-10 text-center text-white">
            <div class="inline-flex items-center gap-2 bg-[#00a877]/90 backdrop-blur-md px-4 py-1.5 rounded-full text-xs font-semibold tracking-wider text-white uppercase mb-5 mx-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                @lang('messages.nav.schedules')
            </div>
            <h1 class="font-serif text-4xl md:text-6xl lg:text-7xl font-bold leading-tight mb-5 text-white">
                @lang('messages.nav.schedules')
            </h1>
            <p class="text-neutral-200 text-base md:text-lg max-w-2xl mx-auto leading-relaxed font-light">
                {{ __('Check upcoming events, open trips, and schedule availability') }}
            </p>
        </div>
        <div class="absolute bottom-0 left-0 right-0 z-10 overflow-hidden">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="relative block w-full h-[30px] md:h-[50px] text-[#f8fdfb] fill-current">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V0C26.9,8.75,57.05,18.3,88.42,26.49,158.41,44.75,243.62,56.88,321.39,56.44Z"></path>
            </svg>
        </div>
    </section>

    {{-- CALENDAR --}}
    <section class="py-12 bg-white">
        <div class="container max-w-5xl mx-auto px-6">
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-neutral-100">
                <div id="calendar"></div>
            </div>
            {{-- Legend --}}
            <div class="flex flex-wrap items-center justify-center gap-4 mt-4 text-sm">
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-blue-500"></span> Open Trip
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-emerald-600"></span> Confirmed
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-amber-500"></span> Pending
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-red-500"></span> Fully Booked
                </span>
            </div>
        </div>
    </section>

    {{-- EVENTS LIST --}}
    @if($allSchedules->isNotEmpty())
    <section class="py-12 bg-[#f8fdfb]" id="events-section">
        <div class="container mx-auto px-6">
            <h2 class="text-2xl font-serif font-bold text-[#053d2c] mb-8 text-center">{{ __('Upcoming Events') }}</h2>

            {{-- Type Tabs --}}
            <div class="type-tabs justify-center" id="eventTabs">
                <button class="type-tab active" data-type="all">
                    {{ __('All') }}
                    <span class="count">{{ $allSchedules->count() }}</span>
                </button>
                <button class="type-tab" data-type="open_trip">
                    <span class="type-badge type-open_trip mr-1">Open Trip</span>
                    <span class="count">{{ $openTrips->count() }}</span>
                </button>
                <button class="type-tab" data-type="confirmed">
                    <span class="type-badge type-confirmed mr-1">Confirmed</span>
                    <span class="count">{{ $confirmed->count() }}</span>
                </button>
                <button class="type-tab" data-type="pending">
                    <span class="type-badge type-pending mr-1">Pending</span>
                    <span class="count">{{ $pending->count() }}</span>
                </button>
            </div>

            {{-- Event Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="eventsGrid">
                @foreach($allSchedules as $schedule)
                    @php $isPast = $schedule->start_date->isPast(); @endphp
                    <div class="event-card bg-white rounded-xl shadow-md border border-neutral-100 overflow-hidden hover:shadow-lg transition-shadow {{ $isPast ? 'opacity-70' : '' }}"
                         data-type="{{ $schedule->type }}">
                        <div class="p-5">
                            {{-- Type Badge --}}
                            <div class="flex items-center justify-between mb-3">
                                <span class="type-badge type-{{ $schedule->type }}">
                                    {{ $schedule->type_label }}
                                </span>
                                @if($isPast)
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-gray-600 bg-gray-100 px-2 py-0.5 rounded-full">
                                        {{ __('Past Event') }}
                                    </span>
                                @elseif(!$schedule->isAvailable())
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-red-700 bg-red-50 px-2 py-0.5 rounded-full">
                                        {{ __('Fully Booked') }}
                                    </span>
                                @endif
                            </div>


                            @if($schedule->visitor_name)
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-sm">
                                    {{ substr($schedule->visitor_name, 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="font-bold text-base text-[#053d2c]">{{ $schedule->visitor_name }}</h3>
                                    <p class="text-xs text-neutral-400">{{ __('Visitor') }}</p>
                                </div>
                            </div>
                            @endif

                            <div class="mt-3 pt-3 border-t border-neutral-100">
                                <p class="text-xs text-neutral-400 uppercase tracking-wider font-semibold mb-1">{{ __('Package') }}</p>
                                <p class="font-semibold text-[#053d2c]">{{ $schedule->travelPackage->type }}</p>
                                <p class="text-sm text-neutral-500">{{ $schedule->travelPackage->location }}</p>
                            </div>

                            <div class="flex items-center gap-2 text-sm text-neutral-600 mt-3 pt-3 border-t border-neutral-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>{{ $schedule->start_date->format('d M Y') }}@if($schedule->end_date) → {{ $schedule->end_date->format('d M Y') }}@endif</span>
                            </div>

                            @php $pct = $schedule->quota > 0 ? round(($schedule->booked / $schedule->quota) * 100) : 0; @endphp
                            <div class="flex items-center justify-between text-sm mt-3 pt-3 border-t border-neutral-100 mb-2">
                                <span class="text-neutral-500">{{ __('Quota') }}:</span>
                                <span class="font-medium">{{ $schedule->booked }}/{{ $schedule->quota }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 mb-4">
                                <div class="h-2 rounded-full transition-all duration-300 {{ $pct >= 100 ? 'bg-red-500' : ($pct >= 80 ? 'bg-yellow-500' : 'bg-emerald-500') }}" style="width: {{ min($pct, 100) }}%"></div>
                            </div>

                            <div class="flex items-center justify-between">
                                @if($schedule->isAvailable())
                                <span class="inline-flex items-center gap-1 text-xs font-medium text-emerald-700 bg-emerald-50 px-2 py-1 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    {{ $schedule->remainingQuota() }} {{ __('spots left') }}
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1 text-xs font-medium text-red-700 bg-red-50 px-2 py-1 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    {{ __('Fully Booked') }}
                                </span>
                                @endif
                            </div>
                            
                            @if(!$isPast && $schedule->type === 'open_trip' && $schedule->isAvailable())

                            <button type="button" 
                                    class="open-trip-register-btn mt-3 w-full text-center bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-4 rounded-lg transition-colors text-sm"
                                    data-schedule-id="{{ $schedule->id }}"
                                    data-package-name="{{ $schedule->travelPackage->type }}"
                                    data-package-location="{{ $schedule->travelPackage->location }}"
                                    data-start-date="{{ $schedule->start_date->format('Y-m-d') }}"
                                    data-end-date="{{ $schedule->end_date?->format('Y-m-d') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                {{ __('Register Now') }}
                            </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Empty State --}}
    @if($allSchedules->isEmpty())
    <section class="py-12 bg-[#f8fdfb]">
        <div class="container mx-auto px-6 text-center">
            <div class="bg-white rounded-2xl shadow-md p-12 max-w-lg mx-auto">
                <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-[#053d2c] mb-2">{{ __('No Events Yet') }}</h3>
                <p class="text-neutral-500 mb-6">{{ __('There are no upcoming events at the moment. Please check back later or contact us for more information.') }}</p>
                <a href="https://api.whatsapp.com/send?phone=6281328856252&text={{ urlencode(__('messages.whatsapp.default_message')) }}" target="_blank" class="inline-flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-xl font-medium transition-colors">
                    <i class="bx bxl-whatsapp text-lg"></i>
                    {{ __('Ask Now') }}
                </a>
            </div>
        </div>
    </section>
    @endif

    {{-- MODAL --}}
    <div class="modal-overlay" id="modalOverlay">
        <div class="modal-box">
            <div class="modal-header">
                <button type="button" class="modal-close-btn" id="modalCloseBtn">✕</button>
                <div class="text-white">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center text-white font-bold text-xl" id="mAvatar">V</div>
                        <div>
                            <h3 class="text-xl font-bold text-white" id="mVisitor">-</h3>
                            <p class="text-white/80 text-sm">{{ __('Visitor') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="modal-icon"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg></div>
                    <div class="flex-1"><div class="modal-label">{{ __('Package') }}</div><div class="modal-value" id="mPackage">-</div></div>
                </div>
                <div class="row">
                    <div class="modal-icon"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg></div>
                    <div class="flex-1"><div class="modal-label">{{ __('Location') }}</div><div class="modal-value" id="mLocation">-</div></div>
                </div>
                <div class="row">
                    <div class="modal-icon"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
                    <div class="flex-1"><div class="modal-label">{{ __('Date') }}</div><div class="modal-value" id="mDate">-</div></div>
                </div>
                <div class="row">
                    <div class="modal-icon"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
                    <div class="flex-1"><div class="modal-label">{{ __('Quota') }}</div><div class="modal-value" id="mQuota">-</div></div>
                </div>
                <div class="row">
                    <div class="modal-icon"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                    <div class="flex-1"><div class="modal-label">{{ __('Status') }}</div><div class="modal-value" id="mStatus">-</div></div>
                </div>
                <div class="row">
                    <div class="modal-icon"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg></div>
                    <div class="flex-1"><div class="modal-label">{{ __('Type') }}</div><div class="modal-value" id="mType">-</div></div>
                </div>
    <div class="mt-6 pt-4 border-t border-neutral-100">
        <a href="#" id="mCta" class="block w-full text-center bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 px-6 rounded-xl transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            {{ __('View Package Details') }}
        </a>
    </div>
    <div id="mRegisterContainer" class="mt-3 is-hidden">
        <button type="button" id="mRegisterBtn" class="w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            {{ __('Register for Open Trip') }}
        </button>
    </div>
            </div>
        </div>
    </div>

    {{-- OPEN TRIP REGISTRATION MODAL --}}
    <div class="modal-overlay" id="registerModalOverlay" style="z-index: 10000;">
        <div class="modal-box">
            <div class="modal-header">
                <button type="button" class="modal-close-btn" id="registerModalCloseBtn">✕</button>
                <div class="text-white">
                    <h3 class="text-xl font-bold text-white" id="registerModalTitle">{{ __('Open Trip Registration') }}</h3>
                    <p class="text-white/80 text-sm" id="registerModalPackage">-</p>
                </div>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('booking.store') }}" id="openTripRegisterForm" class="space-y-4">
                    @csrf
                    <input type="hidden" name="schedule_id" id="registerScheduleId">
                    <input type="hidden" name="travel_package_id" id="registerPackageId">
                    <input type="hidden" name="start_date" id="registerStartDate">
                    
                    <div>
                        <label for="registerName" class="admin-form-label">{{ __('Name') }} <span class="text-red-500">*</span></label>
                        <input type="text" id="registerName" name="name" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none"
                               placeholder="{{ __('Your full name') }}">
                    </div>
                    
                    <div>
                        <label for="registerPhone" class="admin-form-label">{{ __('Phone Number') }} <span class="text-red-500">*</span></label>
                        <input type="tel" id="registerPhone" name="number_phone" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none"
                               placeholder="{{ __('081234567890') }}">
                    </div>
                    
                    <div>
                        <label for="registerEmail" class="admin-form-label">{{ __('Email') }} <span class="text-red-500">*</span></label>
                        <input type="email" id="registerEmail" name="email" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none"
                               placeholder="{{ __('your@email.com') }}">
                    </div>
                    
                    <div>
                        <label for="registerPeople" class="admin-form-label">{{ __('Number of People') }} <span class="text-red-500">*</span></label>
                        <input type="number" id="registerPeople" name="people_count" required min="1" value="1"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none"
                               onchange="updateParticipantForms()">
                    </div>
                    
                    {{-- Dynamic Participants Section --}}
                    <div id="participantsSection">
                        <div class="flex items-center justify-between mb-2">
                            <label class="admin-form-label">{{ __('Participant Names') }}</label>
                            <button type="button" onclick="addParticipantForm()" class="text-xs text-emerald-600 hover:text-emerald-700">
                                <i class="fas fa-plus"></i> {{ __('Add') }}
                            </button>
                        </div>
                        <div id="participantsContainer" class="space-y-2 max-h-48 overflow-y-auto">
                            {{-- First participant form - shown by default --}}
                            <div class="participant-form flex items-center gap-2">
                                <input type="text" name="participants[0][name]" 
                                       class="flex-1 px-2 py-1 text-sm border border-gray-300 rounded" 
                                       placeholder="{{ __('Participant name') }}" required>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label for="registerDescription" class="admin-form-label">{{ __('Description (Optional)') }}</label>
                        <textarea id="registerDescription" name="description" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none"
                                  placeholder="{{ __('Any special requests or notes...') }}"></textarea>
                    </div>
                    
                    <div class="flex items-center gap-3 pt-4">
                        <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                            {{ __('Submit Registration') }}
                        </button>
                        <button type="button" class="modal-close-btn bg-gray-200 text-gray-700 hover:bg-gray-300" id="registerModalCancelBtn">
                            {{ __('Cancel') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script-alt')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script>
// Global functions for modal
var scheduleEvents = @json($calendarEvents);
var scheduleData = @json($scheduleData);

// Dynamic participant forms - make globally accessible
function updateParticipantForms() {
    var count = parseInt(document.getElementById('registerPeople').value) || 1;
    var container = document.getElementById('participantsContainer');
    var participantsSection = document.getElementById('participantsSection');
    
    if (count > 1) {
        participantsSection.classList.remove('is-hidden');
    } else {
        participantsSection.classList.add('is-hidden');
    }
}

function addParticipantForm() {
    var container = document.getElementById('participantsContainer');
    var peopleInput = document.getElementById('registerPeople');
    var index = container.children.length;
    
    var div = document.createElement('div');
    div.className = 'participant-form flex items-center gap-2';
    div.innerHTML = `
        <input type="text" name="participants[${index}][name]" 
               class="flex-1 px-2 py-1 text-sm border border-gray-300 rounded" 
               placeholder="{{ __('Participant name') }}" required>
        <button type="button" onclick="this.parentElement.remove(); updatePeopleCount()" 
                class="text-red-500 hover:text-red-700 text-xs">
            <i class="fas fa-times"></i>
        </button>
    `;
    container.appendChild(div);
    updatePeopleCount();
}

function updatePeopleCount() {
    var container = document.getElementById('participantsContainer');
    var peopleInput = document.getElementById('registerPeople');
    var count = container.children.length;
    peopleInput.value = count;
}

function openModal(props) {
    var initial = (props.visitor_name || 'V').charAt(0).toUpperCase();
    document.getElementById('mAvatar').textContent = initial;
    document.getElementById('mVisitor').textContent = props.visitor_name || '-';
    document.getElementById('mPackage').textContent = props.package_name || '-';
    document.getElementById('mLocation').textContent = props.package_location || '-';
    var dt = props.start_date || '-';
    if (props.end_date && props.end_date !== props.start_date) {
        dt += ' → ' + props.end_date;
    }
    document.getElementById('mDate').textContent = dt;
    document.getElementById('mQuota').textContent = props.booked + '/' + props.quota + ' (' + props.remaining + ' ' + '{{ __("remaining") }}' + ')';
    var st = document.getElementById('mStatus');
    // Check if past event
    var isPast = props.start_date && new Date(props.start_date) < new Date();
    if (isPast) {
        st.innerHTML = '<span class="inline-flex items-center gap-1 text-gray-600 bg-gray-100 px-2 py-0.5 rounded-full text-xs font-medium"><svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"/></svg> {{ __("Past Event") }}</span>';
    } else if (props.is_available) {
        st.innerHTML = '<span class="inline-flex items-center gap-1 text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full text-xs font-medium"><svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> {{ __("Available") }}</span>';
    } else {
        st.innerHTML = '<span class="inline-flex items-center gap-1 text-red-700 bg-red-50 px-2 py-0.5 rounded-full text-xs font-medium"><svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg> {{ __("Fully Booked") }}</span>';
    }
    document.getElementById('mType').textContent = props.type_label || props.type || '-';
    document.getElementById('mCta').href = props.package_url || '#';
    
    // Show register button for open_trip type (only if not past and available)
    var registerContainer = document.getElementById('mRegisterContainer');
    var registerBtn = document.getElementById('mRegisterBtn');
    if (!isPast && props.type === 'open_trip' && props.is_available) {
        registerContainer.classList.remove('is-hidden');
        registerBtn.setAttribute('data-schedule-id', props.id);
    } else {
        registerContainer.classList.add('is-hidden');
    }
    
    document.getElementById('modalOverlay').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.getElementById('modalOverlay').classList.remove('active');
    document.body.style.overflow = '';
}

jQuery(document).ready(function($) {
    // FullCalendar
    var calEl = document.getElementById('calendar');
    if (calEl) {
        var calendar = new FullCalendar.Calendar(calEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listWeek'
            },
            events: scheduleEvents,
            eventClick: function(info) {
                info.jsEvent.preventDefault();
                openModal(info.event.extendedProps);
            },
            eventDidMount: function(info) {
                var p = info.event.extendedProps;
                info.el.setAttribute('title', p.visitor_name || p.package_name || '');
            }
        });
        calendar.render();
    }

    // Modal close handlers
    $('#modalCloseBtn').on('click', closeModal);
    $('#modalOverlay').on('click', function(e) {
        if (e.target === this) closeModal();
    });
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape') closeModal();
    });

    // Type filter tabs
    $('#eventTabs .type-tab').on('click', function() {
        var type = $(this).data('type');
        
        // Update active tab
        $('#eventTabs .type-tab').removeClass('active');
        $(this).addClass('active');

        // Filter cards
        if (type === 'all') {
            $('.event-card').fadeIn(300);
        } else {
            $('.event-card').each(function() {
                if ($(this).data('type') === type) {
                    $(this).fadeIn(300);
                } else {
                    $(this).hide();
                }
            });
        }
    });

    // Open Trip Registration Modal
    $('.open-trip-register-btn').on('click', function() {
        var scheduleId = $(this).data('schedule-id');
        var schedule = scheduleData.find(function(s) { return s.id === scheduleId; });
        
        if (schedule) {
            $('#registerScheduleId').val(schedule.id);
            $('#registerPackageId').val(schedule.travel_package_id);
            $('#registerStartDate').val(schedule.start_date);
            $('#registerModalTitle').text('{{ __("Open Trip Registration") }}');
            $('#registerModalPackage').text(schedule.package_name + ' - ' + schedule.package_location);
            // Reset participants container to 1 form
            $('#participantsContainer').html('<div class="participant-form flex items-center gap-2"><input type="text" name="participants[0][name]" class="flex-1 px-2 py-1 text-sm border border-gray-300 rounded" placeholder="{{ __('Participant name') }}" required></div>');
            $('#registerPeople').val(1);
            // Show participants section (remove hidden class if present)
            $('#participantsSection').show();
            $('#registerModalOverlay').addClass('active');
            document.body.style.overflow = 'hidden';
        }
    });

    // Register button in calendar modal
    $(document).on('click', '#mRegisterBtn', function() {
        var scheduleId = $(this).data('schedule-id');
        var schedule = scheduleData.find(function(s) { return s.id === parseInt(scheduleId); });
        
        if (schedule) {
            document.getElementById('registerScheduleId').value = schedule.id;
            document.getElementById('registerPackageId').value = schedule.travel_package_id;
            document.getElementById('registerStartDate').value = schedule.start_date;
            document.getElementById('registerModalTitle').textContent = '{{ __("Open Trip Registration") }}';
            document.getElementById('registerModalPackage').textContent = schedule.package_name + ' - ' + schedule.package_location;
            // Reset participants container to 1 form
            document.getElementById('participantsContainer').innerHTML = '<div class="participant-form flex items-center gap-2"><input type="text" name="participants[0][name]" class="flex-1 px-2 py-1 text-sm border border-gray-300 rounded" placeholder="{{ __('Participant name') }}" required></div>';
            document.getElementById('registerPeople').value = 1;
            // Show participants section
            document.getElementById('participantsSection').classList.remove('is-hidden');
            // Close calendar modal and open register modal
            document.getElementById('modalOverlay').classList.remove('active');
            document.getElementById('registerModalOverlay').classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    });

    // Close registration modal

    $('#registerModalCloseBtn, #registerModalCancelBtn').on('click', function() {
        $('#registerModalOverlay').removeClass('active');
        document.body.style.overflow = '';
    });

    $('#registerModalOverlay').on('click', function(e) {
        if (e.target === this) {
            $(this).removeClass('active');
            document.body.style.overflow = '';
        }
    });
});
</script>
@endpush

@push('style-alt')
<style>
.fc .fc-daygrid-day.fc-day-today { background: #f0fdf4; }
</style>
@endpush