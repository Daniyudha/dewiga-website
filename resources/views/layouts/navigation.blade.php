{{-- Brand Logo --}}
<div class="sidebar-brand flex items-center gap-3 px-5 py-4 border-b border-primary-700/50">
    <div class="flex items-center gap-3">
        <img src="{{ asset('frontend/assets/img/brand-logo-outline.png') }}" alt="Dewiga Logo"
             class="brand-logo w-12 h-auto">
        <span class="brand-text font-heading text-lg font-bold text-white tracking-wide">DEWIGA</span>
    </div>
</div>

{{-- User Panel --}}
<div class="px-4 py-3 border-b border-primary-700/30">
    <div class="flex items-center gap-3">
        <img src="https://i.pravatar.cc/36?u={{ urlencode(Auth::user()->email) }}"
             alt="{{ Auth::user()->name }}"
             class="sidebar-user-avatar w-9 h-9 rounded-full object-cover border-2 border-primary-400/50 flex-shrink-0">
        <div class="min-w-0">
            <a href="{{ route('admin.profile.show') }}" class="sidebar-user-name text-sm font-medium text-white/90 hover:text-white truncate block">
                {{ Auth::user()->name }}
            </a>
            <span class="sidebar-user-role text-xs text-white/50">Administrator</span>
        </div>
    </div>
</div>

{{-- Sidebar Menu --}}
<nav class="flex-1 overflow-y-auto px-3 py-4 nav-sidebar">
    @php $user = Auth::user(); @endphp

    {{-- DASHBOARD --}}
    @if($user->hasPermission('dashboard.view'))
    <div class="nav-section mb-6">
        <div class="nav-section-title px-3 py-2 text-xs font-semibold text-white/40 uppercase tracking-wider">
            Dashboard
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link">
                <i class="nav-icon fas fa-th"></i>
                <span>{{ __('Dashboard') }}</span>
            </a>
        </div>
    </div>
    @endif

    {{-- RESERVASI & OPERASIONAL --}}
    @if($user->hasAnyPermission(['schedules.view', 'schedules.create', 'schedules.edit', 'proposals.view', 'bookings.view', 'open_trips.view']))
    <div class="nav-section mb-6">
        <div class="nav-section-title px-3 py-2 text-xs font-semibold text-white/40 uppercase tracking-wider">
            Reservasi & Operasional
        </div>
        @if($user->hasAnyPermission(['schedules.view', 'schedules.create', 'schedules.edit']))
        <div class="nav-item">
            <a href="{{ route('admin.schedules.index') }}" class="nav-link">
                <i class="nav-icon fas fa-calendar-alt"></i>
                <span>{{ __('Schedules') }}</span>
            </a>
        </div>
        @endif
        @if($user->hasPermission('schedules.view'))
        <div class="nav-item">
            <a href="{{ route('admin.rundown-templates.index') }}" class="nav-link">
                <i class="nav-icon fas fa-clipboard-list"></i>
                <span>Template Rundown</span>
            </a>
        </div>
        @endif
        @if($user->hasAnyPermission(['proposals.view', 'proposals.create', 'proposals.edit']))
        <div class="nav-item">
            <a href="{{ route('admin.proposals.index') }}" class="nav-link">
                <i class="nav-icon fas fa-file-invoice"></i>
                <span>Proposal Program</span>
            </a>
        </div>
        @endif
        @if($user->hasPermission('schedules.view'))
        <div class="nav-item">
            <a href="{{ route('admin.price-calculator.index') }}" class="nav-link">
                <i class="nav-icon fas fa-calculator"></i>
                <span>Kalkulator Harga</span>
            </a>
        </div>
        @endif
        @if($user->hasAnyPermission(['bookings.view', 'bookings.create', 'bookings.edit']))
        <div class="nav-item">
            <a href="{{ route('admin.bookings.index') }}" class="nav-link">
                <i class="nav-icon fas fa-book"></i>
                <span>{{ __('Booking') }}</span>
            </a>
        </div>
        @endif
        @if($user->hasAnyPermission(['open_trips.view', 'open_trips.create', 'open_trips.edit']))
        <div class="nav-item">
            <a href="{{ route('admin.open-trip-registrations.index') }}" class="nav-link">
                <i class="nav-icon fas fa-users"></i>
                <span>{{ __('Open Trip Registrations') }}</span>
            </a>
        </div>
        @endif
    </div>
    @endif

    {{-- Data Base --}}
    @if($user->hasAnyPermission(['guests.view', 'transactions.view']))
    <div class="nav-section mb-6">
        <div class="nav-section-title px-3 py-2 text-xs font-semibold text-white/40 uppercase tracking-wider">
            Data Base
        </div>
        @if($user->hasAnyPermission(['guests.view', 'guests.create', 'guests.edit']))
        <div class="nav-item">
            <a href="{{ route('admin.guests.index') }}" class="nav-link">
                <i class="nav-icon fas fa-address-book"></i>
                <span>Database Tamu</span>
            </a>
        </div>
        @endif
        @if($user->hasPermission('guests.view'))
        <div class="nav-item">
            <a href="{{ route('admin.visit-reports.index') }}" class="nav-link">
                <i class="nav-icon fas fa-chart-bar"></i>
                <span>Data Kunjungan</span>
            </a>
        </div>
        @endif
        @if($user->hasAnyPermission(['transactions.view', 'transactions.create', 'transactions.edit']))
        <div class="nav-item">
            <a href="{{ route('admin.transactions.index') }}" class="nav-link">
                <i class="nav-icon fas fa-coins"></i>
                <span>Data Keuangan</span>
            </a>
        </div>
        @endif
    </div>
    @endif

    {{-- MASTER DATA --}}
    @if($user->hasAnyPermission(['packages.view', 'packages.create', 'packages.edit']))
    <div class="nav-section mb-6">
        <div class="nav-section-title px-3 py-2 text-xs font-semibold text-white/40 uppercase tracking-wider">
            Master Data
        </div>
        @if($user->hasAnyPermission(['packages.view', 'packages.create', 'packages.edit']))
        <div class="nav-item">
            <a href="{{ route('admin.travel_packages.index') }}" class="nav-link">
                <i class="nav-icon fa fa-hotel"></i>
                <span>{{ __('Travel Package') }}</span>
            </a>
        </div>
        @endif
        <div class="nav-item">
            <a href="{{ route('admin.activities.index') }}" class="nav-link">
                <i class="nav-icon fas fa-running"></i>
                <span>{{ __('Activities') }}</span>
            </a>
        </div>
    </div>
    @endif

    {{-- BLOG --}}
    @if($user->hasPermission('blogs.manage'))
    <div class="nav-section mb-6">
        <div class="nav-section-title px-3 py-2 text-xs font-semibold text-white/40 uppercase tracking-wider">
            Blog
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.blogs.index') }}" class="nav-link">
                <i class="nav-icon fas fa-blog"></i>
                <span>{{ __('Blog') }}</span>
            </a>
        </div>
        @if($user->hasPermission('categories.manage'))
        <div class="nav-item">
            <a href="{{ route('admin.categories.index') }}" class="nav-link">
                <i class="nav-icon fas fa-folder"></i>
                <span>{{ __('Category') }}</span>
            </a>
        </div>
        @endif
    </div>
    @endif

    {{-- CONTENT --}}
    @if($user->hasAnyPermission(['testimonials.manage', 'galleries.manage', 'hero.manage', 'partners.manage']))
    <div class="nav-section mb-6">
        <div class="nav-section-title px-3 py-2 text-xs font-semibold text-white/40 uppercase tracking-wider">
            Content
        </div>
        @if($user->hasPermission('testimonials.manage'))
        <div class="nav-item">
            <a href="{{ route('admin.testimonials.index') }}" class="nav-link">
                <i class="nav-icon fas fa-star"></i>
                <span>{{ __('Testimonials') }}</span>
            </a>
        </div>
        @endif
        @if($user->hasPermission('galleries.manage'))
        <div class="nav-item">
            <a href="{{ route('admin.site-galleries.index') }}" class="nav-link">
                <i class="nav-icon fas fa-camera"></i>
                <span>{{ __('Site Gallery') }}</span>
            </a>
        </div>
        @endif
        @if($user->hasPermission('partners.manage'))
        <div class="nav-item">
            <a href="{{ route('admin.partner_logos.index') }}" class="nav-link">
                <i class="nav-icon fas fa-images"></i>
                <span>{{ __('Partner Logos') }}</span>
            </a>
        </div>
        @endif
        @if($user->hasPermission('hero.manage'))
        <div class="nav-item">
            <a href="{{ route('admin.hero-settings.index') }}" class="nav-link">
                <i class="nav-icon fas fa-sliders-h"></i>
                <span>{{ __('Hero Settings') }}</span>
            </a>
        </div>
        @endif
    </div>
    @endif

    {{-- SETTINGS --}}
    @if($user->hasAnyPermission(['users.view', 'roles.manage']))
    <div class="nav-section mb-6">
        <div class="nav-section-title px-3 py-2 text-xs font-semibold text-white/40 uppercase tracking-wider">
            Settings
        </div>
        @if($user->hasPermission('users.view'))
        <div class="nav-item">
            <a href="{{ route('admin.users.index') }}" class="nav-link">
                <i class="nav-icon fas fa-users-cog"></i>
                <span>{{ __('Users') }}</span>
            </a>
        </div>
        @endif
        @if($user->hasPermission('roles.manage'))
        <div class="nav-item">
            <a href="{{ route('admin.roles.index') }}" class="nav-link">
                <i class="nav-icon fas fa-shield-alt"></i>
                <span>{{ __('Role & Permission') }}</span>
            </a>
        </div>
        @endif
    </div>
    @endif
</nav>

{{-- Footer credit --}}
<div class="sidebar-footer px-4 py-3 border-t border-primary-700/30 text-center">
    <span class="text-xs text-white/40">Admin Panel v2.0</span>
</div>
