@section('title', __('Verify Email Address'))

<x-guest-layout>
    <section class="section">
        <div class="container mt-5">
            <div class="row">
                <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                    <div class="login-brand">
                        <img src="{{ asset('img/logo.jpeg') }}" alt="logo" width="100" class="shadow-light rounded-circle">
                    </div>

                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>{{ __('Verify Email Address') }}</h4>
                        </div>

                        <div class="card-body">
                            <p class="text-muted">{{ __('Verification email has been run successfully.') }}</p>

                            @if (Auth::check() && !Auth::user()->is_active)
                                <div class="alert alert-warning">
                                    <p>{{ __('Your account have not been activated. Please wait for the confirmation from the administrator before you can start to use the application.') }}</p>
                                </div>
                            @endif

                            <a href="{{ route('dashboard') }}" class="btn btn-primary">{{ __('Go to page :page', ['page' => __('Dashboard')]) }}</a>
                        </div>
                    </div>

                    @include('components.footer')
                </div>
            </div>
        </div>
    </section>
</x-guest-layout>
