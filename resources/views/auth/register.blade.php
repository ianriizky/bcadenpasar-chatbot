<x-guest-layout>
    <section class="section">
        <div class="container mt-5">
            <div class="row">
                <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-8 offset-lg-2 col-xl-8 offset-xl-2">
                    <div class="login-brand">
                        <img src="{{ asset('img/stisla/stisla-fill.svg') }}" alt="logo" width="100" class="shadow-light rounded-circle">
                    </div>

                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>{{ __('Register') }}</h4>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('register') }}" method="post">
                                @csrf

                                <div class="row">
                                    <div class="form-group col-lg-6 col-12">
                                        <label for="first_name">First Name</label>

                                        <input type="text" name="first_name" id="first_name" class="form-control" required autofocus autocomplete="on">

                                        <x-invalid-feedback :name="'first_name'"/>
                                    </div>

                                    <div class="form-group col-lg-6 col-12">
                                        <label for="last_name">Last Name</label>

                                        <input type="text" name="last_name" id="last_name" class="form-control" required autocomplete="on">

                                        <x-invalid-feedback :name="'last_name'"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>

                                    <input type="email" name="email" id="email" class="form-control" required autocomplete="on">

                                    <x-invalid-feedback :name="'email'"/>
                                </div>

                                <div class="row">
                                    <div class="form-group col-lg-6 col-12">
                                        <label for="password">Password</label>

                                        <input type="password" name="password" id="password" class="form-control">

                                        <x-invalid-feedback :name="'password'"/>
                                    </div>

                                    <div class="form-group col-lg-6 col-12">
                                        <label for="password_confirmation">Password Confirmation</label>

                                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">

                                        <x-invalid-feedback :name="'password_confirmation'"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" name="agree_with_terms" id="agree_with_terms" class="custom-control-input">

                                        <label class="custom-control-label" for="agree_with_terms">
                                            {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                                'terms_of_service' => '<a href="#">' . __('Terms of Service') . '</a>',
                                                'privacy_policy' => '<a href="#">' . __('Privacy Policy') . '</a>',
                                            ]) !!}
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block">{{ __('Register') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="simple-footer">
                        Copyright &copy; {{ config('app.name') }} 2021. Made with 💙 by Stisla
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-guest-layout>
