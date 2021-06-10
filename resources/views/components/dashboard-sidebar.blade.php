<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('dashboard') }}">{{ config('app.name') }}</a>
        </div>

        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('dashboard') }}">{{ config('app.shortname') }}</a>
        </div>

        <ul class="sidebar-menu">
            <li class="menu-header">{{ __('Home') }}</li>

            <li>
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="fas fa-fire"></i> <span>{{ __('Dashboard') }}</span>
                </a>
            </li>
        </ul>
    </aside>
</div>
