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
<nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1 nav-sidebar">
    {{-- Dashboard --}}
    <div class="nav-item">
        <a href="{{ route('admin.dashboard') }}" class="nav-link">
            <i class="nav-icon fas fa-th"></i>
            <span>{{ __('Dashboard') }}</span>
        </a>
    </div>

    {{-- Users --}}
    <div class="nav-item">
        <a href="{{ route('admin.users.index') }}" class="nav-link">
            <i class="nav-icon fas fa-users"></i>
            <span>{{ __('Users') }}</span>
        </a>
    </div>

    {{-- Bookings --}}
    <div class="nav-item">
        <a href="{{ route('admin.bookings.index') }}" class="nav-link">
            <i class="nav-icon fas fa-book"></i>
            <span>{{ __('Booking') }}</span>
        </a>
    </div>

    {{-- Travel Packages --}}
    <div class="nav-item">
        <a href="{{ route('admin.travel_packages.index') }}" class="nav-link">
            <i class="nav-icon fa fa-hotel"></i>
            <span>{{ __('Travel Package') }}</span>
        </a>
    </div>

    {{-- Blog (with treeview) --}}
    <div class="nav-item">
        <a href="#" class="nav-link treeview-toggle">
            <i class="nav-icon fas fa-circle"></i>
            <span class="flex-1">{{ __('Blog') }}</span>
            <i class="fas fa-angle-left treeview-icon transition-transform duration-200 text-xs"></i>
        </a>
        <ul class="nav-treeview">
            <li class="nav-item">
                <a href="{{ route('admin.categories.index') }}" class="nav-link">
                    <span>{{ __('Category') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.blogs.index') }}" class="nav-link">
                    <span>{{ __('Add Blog') }}</span>
                </a>
            </li>
        </ul>
    </div>

    {{-- Testimonials --}}
    <div class="nav-item">
        <a href="{{ route('admin.testimonials.index') }}" class="nav-link">
            <i class="nav-icon fas fa-star"></i>
            <span>{{ __('Testimonials') }}</span>
        </a>
    </div>

    {{-- Activities --}}
    <div class="nav-item">
        <a href="{{ route('admin.activities.index') }}" class="nav-link">
            <i class="nav-icon fas fa-running"></i>
            <span>{{ __('Activities') }}</span>
        </a>
    </div>

    {{-- Site Gallery --}}
    <div class="nav-item">
        <a href="{{ route('admin.site-galleries.index') }}" class="nav-link">
            <i class="nav-icon fas fa-camera"></i>
            <span>{{ __('Site Gallery') }}</span>
        </a>
    </div>

    {{-- Partner Logos --}}
    <div class="nav-item">
        <a href="{{ route('admin.partner_logos.index') }}" class="nav-link">
            <i class="nav-icon fas fa-images"></i>
            <span>{{ __('Partner Logos') }}</span>
        </a>
    </div>

    {{-- Hero Settings --}}
    <div class="nav-item">
        <a href="{{ route('admin.hero-settings.index') }}" class="nav-link">
            <i class="nav-icon fas fa-sliders-h"></i>
            <span>{{ __('Hero Settings') }}</span>
        </a>
    </div>
</nav>

{{-- Footer credit --}}
<div class="sidebar-footer px-4 py-3 border-t border-primary-700/30 text-center">
    <span class="text-xs text-white/40">Admin Panel v2.0</span>
</div>
