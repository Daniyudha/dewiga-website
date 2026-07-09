<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Admin Dewiga')</title>

    {{-- Favicon --}}
    <link rel="shortcut icon" href="{{ asset('frontend/assets/favicon/favicon.ico') }}" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('frontend/assets/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('frontend/assets/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('frontend/assets/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('frontend/assets/favicon/site.webmanifest') }}">

    {{-- Google Fonts (same as frontend) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Font Awesome (kept for admin icons) --}}
    <link rel="stylesheet" href="{{ asset('css/fontawesome.min.css') }}">

    @vite('resources/css/admin.css')

    @stack('styles')
</head>
<body class="bg-gray-100 min-h-screen">

    {{-- Overlay for mobile sidebar --}}
    <div id="sidebarOverlay" class="sidebar-overlay hidden" onclick="toggleSidebar()"></div>

    {{-- Sidebar --}}
    <aside id="adminSidebar" class="admin-sidebar flex flex-col">
        @include('layouts.navigation')
    </aside>

    {{-- Main Wrapper --}}
    <div id="mainWrapper" class="main-wrapper sidebar-expanded">

        {{-- Top Navbar --}}
        <nav class="sticky top-0 z-20 bg-white border-b border-gray-200 shadow-sm">
            <div class="flex items-center justify-between h-16 px-4 lg:px-6">
                {{-- Left: hamburger + minimize + brand --}}
                <div class="flex items-center gap-1">
                    <button onclick="toggleSidebar()" class="lg:hidden sidebar-toggle-btn">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <button onclick="minimizeSidebar()" class="hidden lg:flex sidebar-toggle-btn" id="sidebarMinimizeBtn" title="Minimize sidebar">
                        <svg class="w-5 h-5 transition-transform duration-300" id="sidebarToggleIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                        </svg>
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="font-heading text-lg font-semibold text-primary-700 ml-2">
                        DEWIGA Admin
                    </a>
                </div>

                {{-- Right: user dropdown --}}
                <div class="relative" id="profileDropdownContainer">
                    <button onclick="toggleProfileDropdown()" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                        <img src="https://i.pravatar.cc/32?u={{ urlencode(Auth::user()->email) }}"
                             alt="{{ Auth::user()->name }}"
                             class="w-8 h-8 rounded-full object-cover border border-gray-200 flex-shrink-0">
                        <span class="hidden sm:inline font-medium">{{ Auth::user()->name }}</span>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div id="profileDropdown" class="profile-dropdown">
                        <a href="{{ route('admin.profile.show') }}">
                            <i class="fas fa-user mr-2 text-gray-400 w-4 text-center"></i>
                            {{ __('My Profile') }}
                        </a>
                        <a href="/" target="_blank">
                            <i class="fas fa-external-link-alt mr-2 text-gray-400 w-4 text-center"></i>
                            {{ __('View Site') }}
                        </a>
                        <div class="divider"></div>
                        <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                            @csrf
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">
                                <i class="fas fa-sign-out-alt mr-2 text-gray-400 w-4 text-center"></i>
                                {{ __('Log Out') }}
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        {{-- Content Area --}}
        <main class="flex-1 px-4 lg:px-6 py-6">
            {{-- Flash Messages --}}
            @if(count($errors) > 0)
                <div class="admin-alert-danger mb-4" role="alert">
                    <ul class="m-0 list-none space-y-1 flex-1">
                        @foreach($errors->all() as $error)
                            <li class="flex items-start gap-2">
                                <i class="fas fa-exclamation-circle mt-0.5 flex-shrink-0"></i>
                                <span>{{ $error }}</span>
                            </li>
                        @endforeach
                    </ul>
                    <button onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-600 flex-shrink-0">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            @if(session()->has('message'))
                <div class="admin-alert-{{ session()->get('alert-type', 'info') }} mb-4" role="alert">
                    <span>{{ session()->get('message') }}</span>
                    <button onclick="this.parentElement.remove()" class="opacity-60 hover:opacity-100 flex-shrink-0">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="border-t border-gray-200 bg-white px-4 lg:px-6 py-4">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-2 text-sm text-gray-500">
                <span>&copy; {{ date('Y') }} <a href="{{ url('/') }}" class="text-primary-600 hover:text-primary-700">Desa Wisata Gabugan</a>. All rights reserved.</span>
                <span>Powered by
                    <a href="https://www.gegacreative.com/" target="_blank" rel="noopener noreferrer"
                        class="text-blue-700 hover:text-blue-500 transition-colors font-medium">
                            Gega Creative
                        </a>
                </span>
            </div>
        </footer>
    </div>

    {{-- Scripts --}}
    @vite('resources/js/app.js')
    @yield('scripts')

    <script>
        // --- Sidebar Toggle ---
        function toggleSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('hidden');
        }

        // Close sidebar on Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const sidebar = document.getElementById('adminSidebar');
                const overlay = document.getElementById('sidebarOverlay');
                if (sidebar.classList.contains('open')) {
                    sidebar.classList.remove('open');
                    overlay.classList.add('hidden');
                }
            }
        });

        // --- Sidebar Minimize ---
        function minimizeSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const mainWrapper = document.getElementById('mainWrapper');
            const icon = document.getElementById('sidebarToggleIcon');

            sidebar.classList.toggle('minimized');
            mainWrapper.classList.toggle('sidebar-expanded');
            mainWrapper.classList.toggle('sidebar-minimized');

            // Toggle icon direction (chevron left/right)
            if (sidebar.classList.contains('minimized')) {
                icon.style.transform = 'rotate(180deg)';
                document.getElementById('sidebarMinimizeBtn').setAttribute('title', 'Expand sidebar');
            } else {
                icon.style.transform = 'rotate(0deg)';
                document.getElementById('sidebarMinimizeBtn').setAttribute('title', 'Minimize sidebar');
            }
        }

        // --- Profile Dropdown ---
        function toggleProfileDropdown() {
            document.getElementById('profileDropdown').classList.toggle('open');
        }

        // Close dropdown on outside click
        document.addEventListener('click', function(e) {
            const container = document.getElementById('profileDropdownContainer');
            const dropdown = document.getElementById('profileDropdown');
            if (container && !container.contains(e.target)) {
                dropdown.classList.remove('open');
            }
        });

        // --- Treeview Toggle (Blog submenu) ---
        document.addEventListener('DOMContentLoaded', function() {
            const treeviewToggles = document.querySelectorAll('.treeview-toggle');
            treeviewToggles.forEach(function(toggle) {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    const parent = this.closest('.nav-item');
                    const submenu = parent.querySelector('.nav-treeview');
                    const icon = this.querySelector('.treeview-icon');
                    if (submenu) {
                        submenu.classList.toggle('open');
                        if (icon) {
                            icon.classList.toggle('rotate-90');
                        }
                    }
                });
            });

            // Highlight active sidebar link
            const currentPath = window.location.pathname;
            document.querySelectorAll('.nav-sidebar .nav-link').forEach(function(link) {
                const href = link.getAttribute('href');
                if (href && currentPath.startsWith(href) && href !== '#') {
                    link.classList.add('active');
                    // Open parent treeview if inside one
                    const treeview = link.closest('.nav-treeview');
                    if (treeview) {
                        treeview.classList.add('open');
                        const parentToggle = treeview.closest('.nav-item').querySelector('.treeview-toggle');
                        if (parentToggle) {
                            const icon = parentToggle.querySelector('.treeview-icon');
                            if (icon) icon.classList.add('rotate-90');
                        }
                    }
                }
            });
        });

        // --- Delete Confirmation Modal ---
        let deleteFormToSubmit = null;

        function showDeleteModal(form) {
            deleteFormToSubmit = form;
            document.getElementById('deleteModal').classList.add('open');
            // Prevent body scrolling while modal is open
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('open');
            document.body.style.overflow = '';
            deleteFormToSubmit = null;
        }

        function confirmDeleteModal() {
            if (deleteFormToSubmit) {
                deleteFormToSubmit.submit();
            }
            closeDeleteModal();
        }

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDeleteModal();
            }
        });
    </script>

    {{-- Delete Confirmation Modal --}}
    <div id="deleteModal" class="modal-overlay" onclick="if(event.target===this) closeDeleteModal()">
        <div class="modal-box">
            <div class="modal-header">
                <div class="modal-header-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h3>{{ __('Confirm Delete') }}</h3>
            </div>
            <div class="modal-body">
                <p>{{ __('Are you sure you want to delete this item? This action cannot be undone.') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeDeleteModal()" class="admin-btn-secondary admin-btn-sm">
                    <i class="fas fa-times"></i>
                    {{ __('Cancel') }}
                </button>
                <button type="button" onclick="confirmDeleteModal()" class="admin-btn-danger admin-btn-sm">
                    <i class="fas fa-trash"></i>
                    {{ __('Yes, Delete') }}
                </button>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
