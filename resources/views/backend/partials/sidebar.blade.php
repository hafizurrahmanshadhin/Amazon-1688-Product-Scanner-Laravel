@php
    $systemSetting = App\Models\SystemSetting::first();
@endphp

<div class="app-menu navbar-menu">
    {{-- Logo & Toggle Button --}}
    <div class="navbar-brand-box">
        <a href="{{ route('dashboard') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ asset($systemSetting->logo ?? 'backend/images/logo-sm.png') }}" alt="Logo" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ asset($systemSetting->logo ?? 'backend/images/logo-light.png') }}" alt="Logo"
                    height="22">
            </span>
        </a>

        <a href="{{ route('dashboard') }}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ asset($systemSetting->logo ?? 'backend/images/logo-sm.png') }}" alt="Logo"
                    height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ asset($systemSetting->logo ?? 'backend/images/logo-light.png') }}" alt="Logo"
                    height="22">
            </span>
        </a>

        <button type="button" class="btn btn-sm p-0 fs-3xl header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>

        <div class="vertical-menu-btn-wrapper header-item vertical-icon">
            <button type="button"
                class="btn btn-sm px-0 fs-xl vertical-menu-btn topnav-hamburger shadow hamburger-icon"
                id="topnav-hamburger-icon">
                <i class='bx bx-chevrons-right'></i>
                <i class='bx bx-chevrons-left'></i>
            </button>
        </div>
    </div>
    {{-- Logo & Toggle Button --}}

    <div id="scrollbar">
        {{-- Sidebar Start --}}
        <div class="container-fluid">
            <div id="two-column-menu"></div>
            <ul class="navbar-nav" id="navbar-nav">
                {{-- Dashboard --}}
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                        class="nav-link menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="ri-dashboard-line"></i>
                        <span data-key="t-dashboard">Dashboard</span>
                    </a>
                </li>

                {{-- Matches Product --}}
                <li class="nav-item">
                    <a href="{{ route('product.matches.index') }}"
                        class="nav-link menu-link {{ request()->routeIs('product.matches.*') ? 'active' : '' }}">
                        <i class="ri-map-pin-line"></i>
                        <span data-key="t-product-matches">Product Similarity</span>
                    </a>
                </li>

                <hr>
                {{-- Settings --}}
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->is('admin/settings*') ? 'active' : '' }}"
                        href="#sidebarPages" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ request()->is('admin/settings*') ? 'true' : 'false' }}"
                        aria-controls="sidebarPages">
                        <i class="ri-settings-3-line"></i>
                        <span data-key="t-pages">Settings</span>
                    </a>

                    <div class="collapse menu-dropdown {{ request()->is('admin/settings*') ? 'show' : '' }}"
                        id="sidebarPages">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('profile.setting') }}"
                                    class="nav-link {{ request()->routeIs('profile.setting') ? 'active' : '' }}"
                                    data-key="t-profile-setting">
                                    Profile Settings
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('system.index') }}"
                                    class="nav-link {{ request()->routeIs('system.index') ? 'active' : '' }}"
                                    data-key="t-system-settings">
                                    System Settings
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
        {{-- Sidebar End --}}
    </div>

    <div class="sidebar-background"></div>
</div>
<div class="vertical-overlay"></div>
