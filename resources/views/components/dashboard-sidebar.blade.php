<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('admin.dashboard') }}">{{ config('app.name') }}</a>
        </div>

        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('admin.dashboard') }}">{{ config('app.shortname') }}</a>
        </div>

        <ul class="sidebar-menu">
            <li class="menu-header">{{ __('Home') }}</li>

            <li @if (Route::is('admin.dashboard')) class="active" @endif>
                <a href="{{ route('admin.dashboard') }}" class="nav-link">
                    <i class="fa fa-fire"></i> <span>{{ __('Dashboard') }}</span>
                </a>
            </li>

            <li class="menu-header">{{ __('admin-lang.transaction') }}</li>

            <li @if (Route::is('admin.order.*')) class="active" @endif>
                <a href="{{ route('admin.order.index') }}" class="nav-link">
                    <i class="fa fa-shopping-cart"></i> <span>{{ __('admin-lang.order') }}</span>
                </a>
            </li>

            <li class="menu-header">{{ __('admin-lang.master') }}</li>

            <li @if (Route::is('admin.branch.*')) class="active" @endif>
                <a href="{{ route('admin.branch.index') }}" class="nav-link">
                    <i class="fa fa-building"></i> <span>{{ __('admin-lang.branch') }}</span>
                </a>
            </li>

            <li @if (Route::is('admin.user.*')) class="active" @endif>
                <a href="{{ route('admin.user.index') }}" class="nav-link">
                    <i class="fa fa-id-badge"></i> <span>{{ __('admin-lang.user') }}</span>
                </a>
            </li>

            <li @if (Route::is('admin.customer.*')) class="active" @endif>
                <a href="{{ route('admin.customer.index') }}" class="nav-link">
                    <i class="fa fa-user-tie"></i> <span>{{ __('admin-lang.customer') }}</span>
                </a>
            </li>

            <li @if (Route::is('admin.denomination.*')) class="active" @endif>
                <a href="{{ route('admin.denomination.index') }}" class="nav-link">
                    <i class="fa fa-money-bill-wave"></i> <span>{{ __('admin-lang.denomination') }}</span>
                </a>
            </li>

            <li class="menu-header">{{ __('admin-lang.utility') }}</li>

            @role('admin')
                <li @if (Route::is('admin.role.*')) class="active" @endif>
                    <a href="{{ route('admin.role.index') }}" class="nav-link">
                        <i class="fa fa-user-tag"></i> <span>{{ __('admin-lang.role') }}</span>
                    </a>
                </li>
            @endrole

            <li @if (Route::is('admin.configuration.*')) class="active" @endif>
                <a href="{{ route('admin.configuration.index') }}" class="nav-link">
                    <i class="fa fa-cog"></i> <span>{{ __('admin-lang.configuration') }}</span>
                </a>
            </li>
        </ul>
    </aside>
</div>
