@section('title', __('Login'))

<x-guest-layout>
    <section class="section">
        <div class="d-flex flex-wrap align-items-stretch">
            <div class="col-lg-4 col-md-6 col-12 order-lg-1 min-vh-100 order-2 bg-white">
                <div class="p-4 m-3">
                    <img src="{{ asset('img/logo.jpeg') }}" alt="logo" width="80" class="shadow-light rounded-circle mb-5 mt-2">

                    <h4 class="text-dark font-weight-normal">{{ __('Welcome to') }} <span class="font-weight-bold">{{ config('app.name') }}</span></h4>

                    @if (session('verifying'))
                        <p class="text-danger">{{ __('Please login first before start email verification process.') }}</p>
                    @else
                        <p class="text-muted">{{ __('Before you get started, you must login or register if you don\'t already have an account.') }}</p>
                    @endif

                    <x-auth-session-status class="mb-4" :status="session('status')" />
                    {{-- <x-auth-validation-errors class="mb-4 text-danger" :errors="$errors" /> --}}

                    <form action="{{ route('login') }}" method="post">
                        @csrf

                        <div class="form-group">
                            <label for="identifier">{{ __('Email') . ' / ' . __('Phone Number') . ' / Username' }}<span class="text-danger">*</span></label>

                            <input type="text"
                                name="identifier"
                                id="identifier"
                                class="form-control @error('identifier') is-invalid @enderror"
                                value="{{ old('identifier') }}"
                                tabindex="1"
                                required
                                autofocus>

                            <x-invalid-feedback :name="'identifier'"/>
                        </div>

                        <div class="form-group">
                            <label for="password">{{ __('Password') }}<span class="text-danger">*</span></label>

                            <input type="password"
                                name="password"
                                id="password"
                                class="form-control @error('password') is-invalid @enderror"
                                tabindex="2"
                                required>

                            <x-invalid-feedback :name="'identifier'"/>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox"
                                    name="remember"
                                    id="remember"
                                    class="custom-control-input"
                                    value="1"
                                    @if (old('checked', false)) checked @endif
                                    tabindex="3">

                                <label for="remember" class="custom-control-label">{{ __('Remember me') }}</label>
                            </div>
                        </div>

                        <div class="form-group text-right">
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="float-left mt-3">
                                    {{ __('Forgot your password?') }}
                                </a>
                            @endif

                            <button type="submit" class="btn btn-primary btn-lg btn-icon icon-right" tabindex="4">
                                {{ __('Log in') }}
                            </button>
                        </div>

                        <div class="mt-5 text-center">
                            {{ __('Don\'t have an account?') }} <a href="{{ route('register') }}">{{ __('Create Account') }}</a>
                        </div>
                    </form>

                    <div class="text-center mt-5 text-small">
                        Copyright &copy; {{ config('app.name') }} 2021. Made with 💙 by Stisla

                        <div class="mt-2">
                            <a href="#">{{ __('Privacy Policy') }}</a>

                            <div class="bullet"></div>

                            <a href="#">{{ __('Terms of Service') }}</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 col-12 order-lg-2 order-1 min-vh-100 background-walk-y position-relative overlay-gradient-bottom" data-background="{{ asset('img/unsplash/login-bg.jpg') }}">
                <div class="absolute-bottom-left index-2">
                    <div class="text-light p-5 pb-2">
                        <div class="mb-5 pb-3">
                            <h1 class="mb-2 display-4 font-weight-bold">{{ greeting() }}</h1>

                            <h5 class="font-weight-normal text-muted-transparent">Bali, Indonesia</h5>
                        </div>

                        Photo by <a class="text-light bb" target="_blank" href="https://unsplash.com/photos/a8lTjWJJgLA">Justin Kauffman</a> on <a class="text-light bb" target="_blank" href="https://unsplash.com">Unsplash</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-guest-layout>
