@component('layouts.master')
    <div class="main-wrapper">
        <div class="navbar-bg"></div>

        @include('components.dashboard-header')

        @include('components.dashboard-sidebar')

        <div class="main-content">
            @includeWhen($alert = session('alert'), 'components.alert-dismissible', compact('alert'))

            {{ $slot }}
        </div>

        <footer class="main-footer">
            <div class="footer-left">
                Copyright &copy; 2018 <div class="bullet"></div> Design By <a href="https://nauval.in/">Muhamad Nauval Azhar</a>
            </div>

            <div class="footer-right">
                {{ config('app.name') }}
            </div>
        </footer>
    </div>
@endcomponent
