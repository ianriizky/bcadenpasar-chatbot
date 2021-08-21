<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('admin.dashboard') }}">{{ config('app.name') }}</a>
        </div>

        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('admin.dashboard') }}">{{ config('app.shortname') }}</a>
        </div>

        @auth
            <ul class="sidebar-menu">
                <li class="menu-header">{{ __('Home') }}</li>

                @can('view-dashboard')
                    <li @if (Route::is('admin.dashboard')) class="active" @endif>
                        <a href="{{ route('admin.dashboard') }}" class="nav-link">
                            <i class="fa fa-fire"></i> <span>{{ __('Dashboard') }}</span>
                        </a>
                    </li>
                @endcan

                @if (Auth::user()->can('viewAny', \App\Models\Order::class))
                    <li class="menu-header">{{ __('admin-lang.transaction') }}</li>
                @endif

                @can('viewAny', \App\Models\Order::class)
                    <li @if (Route::is('admin.order.*')) class="active" @endif>
                        <a href="{{ route('admin.order.index') }}" class="nav-link">
                            <i class="fa fa-shopping-cart"></i> <span>{{ __('admin-lang.order') }}</span>
                        </a>
                    </li>
                @endcan

                @if (
                    Auth::user()->can('viewAny', \App\Models\Branch::class) ||
                    Auth::user()->can('viewAny', \App\Models\User::class) ||
                    Auth::user()->can('viewAny', \App\Models\Customer::class) ||
                    Auth::user()->can('viewAny', \App\Models\Denomination::class)
                )
                    <li class="menu-header">{{ __('admin-lang.master') }}</li>
                @endif

                @can('viewAny', \App\Models\Branch::class)
                    <li @if (Route::is('admin.branch.*')) class="active" @endif>
                        <a href="{{ route('admin.branch.index') }}" class="nav-link">
                            <i class="fa fa-building"></i> <span>{{ __('admin-lang.branch') }}</span>
                        </a>
                    </li>
                @endcan

                @can('viewAny', \App\Models\User::class)
                    <li @if (Route::is('admin.user.*')) class="active" @endif>
                        <a href="{{ route('admin.user.index') }}" class="nav-link">
                            <i class="fa fa-id-badge"></i> <span>{{ __('admin-lang.user') }}</span>
                        </a>
                    </li>
                @endcan

                @can('viewAny', \App\Models\Customer::class)
                    <li @if (Route::is('admin.customer.*')) class="active" @endif>
                        <a href="{{ route('admin.customer.index') }}" class="nav-link">
                            <i class="fa fa-user-tie"></i> <span>{{ __('admin-lang.customer') }}</span>
                        </a>
                    </li>
                @endcan

                @can('viewAny', \App\Models\Denomination::class)
                    <li @if (Route::is('admin.denomination.*')) class="active" @endif>
                        <a href="{{ route('admin.denomination.index') }}" class="nav-link">
                            <i class="fa fa-money-bill-wave"></i> <span>{{ __('admin-lang.denomination') }}</span>
                        </a>
                    </li>
                @endcan

                @if (Auth::user()->can('viewAny', \App\Models\Role::class))
                    <li class="menu-header">{{ __('admin-lang.utility') }}</li>
                @endif

                @can('viewAny', \App\Models\Role::class)
                    <li @if (Route::is('admin.role.*')) class="active" @endif>
                        <a href="{{ route('admin.role.index') }}" class="nav-link">
                            <i class="fa fa-user-tag"></i> <span>{{ __('admin-lang.role') }}</span>
                        </a>
                    </li>
                @endcan
            </ul>
        @endauth
    </aside>
</div>
