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

            <li @if (Route::is('dashboard')) class="active" @endif>
                <a href="{{ route('dashboard') }}" class="nav-link">
                    <i class="fa fa-fire"></i> <span>{{ __('Dashboard') }}</span>
                </a>
            </li>

            <li class="menu-header">{{ __('dashboard-lang.role') }}</li>

            <li @if (Route::is('user.*')) class="active" @endif>
                <a href="{{ route('user.index') }}" class="nav-link">
                    <i class="fa fa-id-badge"></i> <span>{{ __('dashboard-lang.user') }}</span>
                </a>
            </li>

            <li @if (Route::is('customer.*')) class="active" @endif>
                <a href="{{ route('customer.index') }}" class="nav-link">
                    <i class="fa fa-user-tie"></i> <span>{{ __('dashboard-lang.customer') }}</span>
                </a>
            </li>
        </ul>
    </aside>
</div>
