@section('pre-style')
    <link rel="stylesheet" href="{{ mix('css/stisla/admin-pre.css') }}">
@endsection

@section('style')
    <link rel="stylesheet" href="{{ mix('css/stisla/admin.css') }}">
@endsection

@section('pre-script')
    <script src="{{ mix('js/stisla/admin.js') }}"></script>
@endsection

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
