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
                                    <div class="form-group col-lg-4 col-12">
                                        <label for="username">{{ __('Username') }}</label>

                                        <input type="text"
                                            name="username"
                                            id="username"
                                            class="form-control @error('username') is-invalid @enderror"
                                            value="{{ old('username') }}"
                                            required
                                            tabindex="1"
                                            autofocus
                                            autocomplete="on">

                                        <x-invalid-feedback :name="'username'"/>
                                    </div>

                                    <div class="form-group col-lg-8 col-12">
                                        <label for="fullname">{{ __('Full name') }}</label>

                                        <input type="text"
                                            name="fullname"
                                            id="fullname"
                                            class="form-control @error('fullname') is-invalid @enderror"
                                            value="{{ old('fullname') }}"
                                            required
                                            tabindex="2"
                                            autocomplete="on">

                                        <x-invalid-feedback :name="'fullname'"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="email">{{ __('Email') }}</label>

                                    <input type="email"
                                        name="email"
                                        id="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email') }}"
                                        required
                                        tabindex="3"
                                        autocomplete="on">

                                    <x-invalid-feedback :name="'email'"/>
                                </div>

                                <div class="row">
                                    <div class="form-group col-lg-6 col-12">
                                        <label for="password">{{ __('Password') }}</label>

                                        <input type="password"
                                            name="password"
                                            id="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            required
                                            tabindex="4">

                                        <x-invalid-feedback :name="'password'"/>
                                    </div>

                                    <div class="form-group col-lg-6 col-12">
                                        <label for="password_confirmation">{{ __('Confirm Password') }}</label>

                                        <input type="password"
                                            name="password_confirmation"
                                            id="password_confirmation"
                                            class="form-control @error('password_confirmation') is-invalid @enderror"
                                            required
                                            tabindex="5">

                                        <x-invalid-feedback :name="'password_confirmation'"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox"
                                            name="agree_with_terms"
                                            id="agree_with_terms"
                                            class="custom-control-input @error('agree_with_terms') is-invalid @enderror"
                                            value="1"
                                            @if (old('agree_with_terms', false)) checked @endif
                                            required
                                            tabindex="6">

                                        <label class="custom-control-label" for="agree_with_terms">
                                            {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                                'terms_of_service' => '<a href="#">' . __('Terms of Service') . '</a>',
                                                'privacy_policy' => '<a href="#">' . __('Privacy Policy') . '</a>',
                                            ]) !!}
                                        </label>

                                        <x-invalid-feedback :name="'agree_with_terms'"/>
                                    </div>
                                </div>

                                <div class="form-divider">{{ __('Data Diri') }}</div>

                                <div class="row">
                                    <div class="form-group col-lg-4 col-12">
                                        <label for="gender">{{ __('Gender') }}</label>

                                        <select name="gender"
                                            id="gender"
                                            class="form-control select2 @error('gender') is-invalid @enderror"
                                            data-placeholder="--{{ __('Choose :field', ['field' => __('Gender') ]) }}--"
                                            data-allow-clear="true"
                                            tabindex="7">
                                            @foreach (\App\Enum\Gender::toArray() as $value => $label)
                                                <option value="{{ $value }}" @if (old('gender') === $value) selected @endif>{{ $label }}</option>
                                            @endforeach
                                        </select>

                                        <x-invalid-feedback :name="'gender'"/>
                                    </div>

                                    <div class="form-group col-lg-8 col-12">
                                        <label for="phone">{{ __('Phone Number') }}</label>

                                        <input type="tel"
                                            name="phone"
                                            id="phone"
                                            class="form-control @error('phone') is-invalid @enderror"
                                            value="{{ old('phone') }}"
                                            required
                                            tabindex="8"
                                            autocomplete="on">

                                        <x-invalid-feedback :name="'phone'"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit"
                                        class="btn btn-primary btn-lg btn-block"
                                        tabindex="9">{{ __('Register') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    @include('components.footer')
                </div>
            </div>
        </div>
    </section>

    @section('pre-style')
        <link rel="stylesheet" href="{{ asset('css/stisla/select2.min.css') }}">
    @endsection

    @section('script')
        <script src="{{ asset('js/stisla/select2.full.min.js') }}"></script>

        <script>
            $(document).ready(function () {
                $('.select2').select2()
                $('.select2').val(null).trigger('change');
            });
        </script>
    @endsection
</x-guest-layout>
