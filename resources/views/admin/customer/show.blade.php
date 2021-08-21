<x-admin-layout>
    <section class="section">
        <div class="section-header">
            <h1>{{ __('Show :name', ['name' => __('admin-lang.customer')]) }}</h1>

            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ route('admin.customer.index') }}">
                        <i class="fas fa-user-tie"></i> <span>{{ __('admin-lang.customer') }}</span>
                    </a>
                </div>

                <div class="breadcrumb-item">
                    <a href="{{ route('admin.customer.show', $customer) }}">
                        <i class="fas fa-eye"></i> <span>{{ __('Show :name', ['name' => __('admin-lang.customer')]) }}</span>
                    </a>
                </div>
            </div>
        </div>


        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        {{-- telegram_chat_id --}}
                        <div class="form-group col-12 col-lg-6">
                            <label for="telegram_chat_id">Telegram Chat ID</label>

                            <p class="form-control-plaintext">{{ $customer->telegram_chat_id_censored }}</p>
                        </div>
                        {{-- /.telegram_chat_id --}}

                        <div class="col-12 col-lg-6"></div>

                        {{-- username --}}
                        <div class="form-group col-12 col-lg-6">
                            <label for="username">Username</label>

                            <p class="form-control-plaintext">{{ $customer->username }}</p>
                        </div>
                        {{-- /.username --}}

                        {{-- fullname --}}
                        <div class="form-group col-12 col-lg-6">
                            <label for="fullname">{{ __('Full name') }}</label>

                            <p class="form-control-plaintext">{{ $customer->fullname }}</p>
                        </div>
                        {{-- /.fullname --}}

                        {{-- gender --}}
                        <div class="form-group col-12 col-lg-6">
                            <label for="gender">{{ __('Gender') }}</label>

                            <p class="form-control-plaintext">{{ $customer->gender->label }}</p>
                        </div>
                        {{-- /.gender --}}

                        {{-- email --}}
                        <div class="form-group col-12 col-lg-6">
                            <label for="email">{{ __('Email Address') }}</label>

                            <p class="form-control-plaintext">{{ $customer->email }}</p>
                        </div>
                        {{-- /.email --}}

                        {{-- phone --}}
                        <div class="form-group col-12 col-lg-6">
                            <label for="phone">{{ __('Phone Number') }}</label>

                            <p class="form-control-plaintext">🇮🇩 {{ $customer->phone }}</p>
                        </div>
                        {{-- /.phone --}}

                        {{-- whatsapp_phone --}}
                        <div class="form-group col-12 col-lg-6">
                            <label for="whatsapp_phone">{{ __('Whatsapp Phone Number') }}</label>

                            <p class="form-control-plaintext">🇮🇩 {{ $customer->whatsapp_phone }}</p>
                        </div>
                        {{-- /.whatsapp_phone --}}

                        {{-- location_latitude | location_longitude --}}
                        <div class="form-group col-12 col-lg-6">
                            <label for="location_latitude_longitude">{{ __('Latitude') }} & {{ __('Longitude') }}</label>

                            <p class="form-control-plaintext">{{ $customer->location_latitude }} | {{ $customer->location_longitude }}</p>
                        </div>
                        {{-- /.location_latitude | location_longitude --}}

                        {{-- google_map_url --}}
                        <div class="form-group col-12 col-lg-6">
                            <label for="google_map_url">{{ __('Google Map Address') }}</label>

                            <p class="form-control-plaintext">
                                <a href="{{ $customer->google_map_url }}" target="_blank">{{ $customer->google_map_url }}</a>
                            </p>
                        </div>
                        {{-- /.google_map_url --}}

                        {{-- account_number --}}
                        <div class="form-group col-12 col-lg-6">
                            <label for="account_number">{{ __('Account Number') }}</label>

                            <p class="form-control-plaintext">{{ $customer->account_number ?? '-' }}</p>
                        </div>
                        {{-- /.account_number --}}

                        {{-- identitycard_number --}}
                        <div class="form-group col-12 col-lg-6">
                            <label for="identitycard_number">{{ __('Identity Card Number') }}</label>

                            <p class="form-control-plaintext">{{ $customer->identitycard_number ?? '-' }}</p>
                        </div>
                        {{-- /.identitycard_number --}}

                        <div class="col-12 col-lg-6"></div>

                        {{-- identitycard_image_preview --}}
                        <div class="col-12 col-lg-6">
                            <img src="{{ $customer->identitycard_image ?? asset('img/dummy.png') }}" id="identitycard_image_preview" class="w-100" alt="image preview">
                        </div>
                        {{-- /.identitycard_image_preview --}}
                    </div>

                    @includeWhen($customer->issuerable, 'components.form-issuerable', ['model' => $customer])
                    @include('components.form-timestamps', ['model' => $customer])
                </div>

                <div class="card-footer">
                    @include('components.datatables.link-back', ['url' => route('admin.customer.index')])

                    @can('update', $customer)
                        @include('components.datatables.link-edit', ['url' => route('admin.customer.edit', $customer)])
                    @endcan
                </div>
            </div>
        </div>
    </section>
</x-admin-layout>
