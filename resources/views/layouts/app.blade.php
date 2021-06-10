@component('components.master-layout')
    <div class="main-wrapper">
        <div class="navbar-bg"></div>

        @include('components.dashboard-header')

        @include('components.dashboard-sidebar')

        <!-- Main Content -->
        <div class="main-content">
            {{ $slot }}
        </div>

        <footer class="main-footer">
            <div class="footer-left">
                Copyright &copy; 2021 <div class="bullet"></div> Design By <a href="https://nauval.in/">Muhamad Nauval Azhar</a>
            </div>

            <div class="footer-right">
                {{ config('app.name') }}
            </div>
        </footer>
    </div>
@endcomponent
