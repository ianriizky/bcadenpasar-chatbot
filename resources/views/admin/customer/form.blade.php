@section('pre-style')
    <link rel="stylesheet" href="{{ mix('node_modules/select2/dist/css/select2.min.css') }}">
@endsection

@section('script')
    <script src="{{ mix('node_modules/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/geolocation.js') }}"></script>
    <script src="{{ asset('js/custom-file-input.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2();

            @include('components.select2-change', ['olds' => Arr::except(old() ?: $customer, '_token')])

            $('button#find-location').click(async function () {
                const previousHtml = $(this).html();

                $(this).attr('disabled', 'disabled')
                $(this).html('<i class="fa fa-spinner fa-spin"></i> <span class="d-none d-xl-inline">{{ __('Please wait') }}</span>');

                try {
                    const position = await geoFindMe();

                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;

                    $('input#location_latitude').val(latitude);
                    $('input#location_longitude').val(longitude);
                } catch (error) {
                    return alert('{{ __('Unable to retrieve your location') }}');
                } finally {
                    $(this).removeAttr('disabled');
                    $(this).html(previousHtml);
                }
            });
        });
    </script>
@endsection

<x-admin-layout>
    <section class="section">
        <div class="section-header">
            <h1>{{ $title }}</h1>

            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ route('admin.customer.index') }}">
                        <i class="fas fa-user-tie"></i> <span>{{ __('admin-lang.customer') }}</span>
                    </a>
                </div>

                <div class="breadcrumb-item">
                    <a href="{{ $url }}">
                        <i class="fas {{ $icon }}"></i> <span>{{ $title }}</span>
                    </a>
                </div>
            </div>
        </div>

        <form action="{{ $action }}" method="post">
            @csrf
            @isset($method) @method($method) @endisset

            <div class="section-body">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            {{-- telegram_chat_id --}}
                            <div class="form-group col-12 col-lg-6">
                                <label for="telegram_chat_id">Telegram Chat ID<span class="text-danger">*</span></label>

                                <input type="text"
                                    name="telegram_chat_id"
                                    id="telegram_chat_id"
                                    class="form-control @error('telegram_chat_id') is-invalid @enderror"
                                    value="{{ old('telegram_chat_id', $customer->telegram_chat_id) }}"
                                    required
                                    autofocus>

                                <x-invalid-feedback :name="'telegram_chat_id'"/>
                            </div>
                            {{-- /.telegram_chat_id --}}

                            <div class="col-12 col-lg-6"></div>

                            {{-- username --}}
                            <div class="form-group col-12 col-lg-6">
                                <label for="username">Username<span class="text-danger">*</span></label>

                                <input type="text"
                                    name="username"
                                    id="username"
                                    class="form-control @error('username') is-invalid @enderror"
                                    value="{{ old('username', $customer->username) }}"
                                    required>

                                <x-invalid-feedback :name="'username'"/>
                            </div>
                            {{-- /.username --}}

                            {{-- fullname --}}
                            <div class="form-group col-12 col-lg-6">
                                <label for="fullname">{{ __('Full name') }}<span class="text-danger">*</span></label>

                                <input type="text"
                                    name="fullname"
                                    id="fullname"
                                    class="form-control @error('fullname') is-invalid @enderror"
                                    value="{{ old('fullname', $customer->fullname) }}"
                                    required>

                                <x-invalid-feedback :name="'fullname'"/>
                            </div>
                            {{-- /.fullname --}}

                            {{-- gender --}}
                            <div class="form-group col-12 col-lg-6">
                                <label for="gender">{{ __('Gender') }}<span class="text-danger">*</span></label>

                                <select name="gender"
                                    id="gender"
                                    class="form-control select2 @error('gender') is-invalid @enderror"
                                    data-placeholder="--{{ __('Choose :field', ['field' => __('Gender') ]) }}--"
                                    data-allow-clear="true"
                                    required>
                                    @foreach (\App\Enum\Gender::toArray() as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>

                                <x-invalid-feedback :name="'gender'"/>
                            </div>
                            {{-- /.gender --}}

                            {{-- email --}}
                            <div class="form-group col-12 col-lg-6">
                                <label for="email">{{ __('Email Address') }}<span class="text-danger">*</span></label>

                                <input type="email"
                                    name="email"
                                    id="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $customer->email) }}"
                                    required>

                                <x-invalid-feedback :name="'email'"/>
                            </div>
                            {{-- /.email --}}

                            {{-- phone_country --}}
                            <input type="hidden" name="phone_country" value="{{ env('PHONE_COUNTRY', 'ID') }}">
                            {{-- /.phone_country --}}

                            {{-- phone --}}
                            <div class="form-group col-12 col-lg-6">
                                <label for="phone">{{ __('Phone Number') }}<span class="text-danger">*</span></label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">🇮🇩</div>
                                    </div>

                                    <input type="tel"
                                        name="phone"
                                        id="phone"
                                        class="form-control @error('phone') is-invalid @enderror"
                                        value="{{ old('phone', $customer->phone) }}"
                                        required>

                                    <x-invalid-feedback :name="'phone'"/>
                                </div>
                            </div>
                            {{-- /.phone --}}

                            {{-- whatsapp_phone_country --}}
                            <input type="hidden" name="whatsapp_phone_country" value="{{ env('PHONE_COUNTRY', 'ID') }}">
                            {{-- /.whatsapp_phone_country --}}

                            {{-- whatsapp_phone --}}
                            <div class="form-group col-12 col-lg-6">
                                <label for="whatsapp_phone">{{ __('Whatsapp Phone Number') }}<span class="text-danger">*</span></label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">🇮🇩</div>
                                    </div>

                                    <input type="tel"
                                        name="whatsapp_phone"
                                        id="whatsapp_phone"
                                        class="form-control @error('whatsapp_phone') is-invalid @enderror"
                                        value="{{ old('whatsapp_phone', $customer->whatsapp_phone) }}"
                                        required>

                                    <x-invalid-feedback :name="'whatsapp_phone'"/>
                                </div>
                            </div>
                            {{-- /.whatsapp_phone --}}

                            {{-- location_latitude | location_longitude --}}
                            <div class="form-group col-12 col-lg-6">
                                <label for="location_latitude_longitude">{{ __('Latitude') }} & {{ __('Longitude') }}<span class="text-danger">*</span></label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <button type="button" class="btn btn-primary" id="find-location">
                                            <i class="fa fa-location-arrow"></i> <span class="d-none d-xl-inline">{{ __('Send My Location') }}</span>
                                        </button>
                                    </div>

                                    <input type="text"
                                        name="location_latitude"
                                        id="location_latitude"
                                        class="form-control @error('location_latitude') is-invalid @enderror"
                                        value="{{ old('location_latitude', $customer->location_latitude) }}"
                                        placeholder="{{ __('Latitude') }}"
                                        required>

                                    <input type="text"
                                        name="location_longitude"
                                        id="location_longitude"
                                        class="form-control @error('location_longitude') is-invalid @enderror"
                                        value="{{ old('location_longitude', $customer->location_longitude) }}"
                                        placeholder="{{ __('Longitude') }}"
                                        required>
                                </div>

                                <x-invalid-feedback :name="'location_latitude'"/>
                                <x-invalid-feedback :name="'location_longitude'"/>
                            </div>
                            {{-- /.location_latitude | location_longitude --}}

                            {{-- google_map_url --}}
                            <div class="form-group col-12 col-lg-6">
                                @if ($customer->exists && $customer->google_map_url)
                                    <label for="google_map_url">{{ __('Google Map Address') }}</label>

                                    <p class="form-control-plaintext">
                                        <a href="{{ $customer->google_map_url }}" target="_blank">{{ $customer->google_map_url }}</a>
                                    </p>
                                @endif
                            </div>
                            {{-- /.google_map_url --}}

                            {{-- account_number --}}
                            <div class="form-group col-12 col-lg-6">
                                <label for="account_number">{{ __('Account Number') }}</label>

                                <input type="text"
                                    name="account_number"
                                    id="account_number"
                                    class="form-control @error('account_number') is-invalid @enderror"
                                    value="{{ old('account_number', $customer->account_number) }}">

                                <x-invalid-feedback :name="'account_number'"/>

                                <small class="form-text text-muted">{{ __('If account number field is empty, then identity card field must be filled') }}</small>
                            </div>
                            {{-- /.account_number --}}

                            {{-- identitycard_number --}}
                            <div class="form-group col-12 col-lg-6">
                                <label for="identitycard_number">{{ __('Identity Card Number') }}</label>

                                <input type="text"
                                    name="identitycard_number"
                                    id="identitycard_number"
                                    class="form-control @error('identitycard_number') is-invalid @enderror"
                                    value="{{ old('identitycard_number', $customer->identitycard_number) }}">

                                <x-invalid-feedback :name="'identitycard_number'"/>
                            </div>
                            {{-- /.identitycard_number --}}

                            {{-- identitycard_image --}}
                            <div class="form-group col-12 col-lg-6 offset-lg-6">
                                @if ($customer->exists)
                                    <label for="identitycard_image">{{ __('Update :name', ['name' => __('Identity Card Image')]) }}</label>
                                @else
                                    <label for="identitycard_image">{{ __('Identity Card Image') }}</label>
                                @endif

                                <div class="custom-file">
                                    <label class="custom-file-label" for="identitycard_image">{{ __('Choose file') }}</label>

                                    <input type="file"
                                        name="identitycard_image"
                                        id="identitycard_image"
                                        class="custom-file-input"
                                        data-preview="#identitycard_image_preview"
                                        accept="image/*">
                                </div>
                            </div>
                            {{-- /.identitycard_image --}}

                            <div class="col-12 col-lg-6"></div>

                            {{-- identitycard_image_preview --}}
                            <div class="col-12 col-lg-6">
                                @if ($customer->exists && $customer->getRawOriginal('identitycard_image'))
                                    <button type="submit"
                                        class="btn btn-danger mb-3"
                                        formaction="{{ route('admin.customer.destroy-identitycard_image', $customer) }}"
                                        name="_method"
                                        value="DELETE"
                                        onclick="return confirm('{{ __('Are you sure you want to delete this data?') }}')">
                                        <i class="fa fa-trash"></i> <span>{{ __('Delete :name', ['name' => __('Identity Card Image')]) }}</span>
                                    </button>
                                @endif

                                <img src="{{ $customer->identitycard_image ?? asset('img/dummy.png') }}" id="identitycard_image_preview" class="w-100" alt="image preview">
                            </div>
                            {{-- /.identitycard_image_preview --}}
                        </div>

                        @includeWhen($customer->exists && $customer->issuerable, 'components.form-issuerable', ['model' => $customer])
                        @includeWhen($customer->exists, 'components.form-timestamps', ['model' => $customer])
                    </div>

                    <div class="card-footer">
                        @can('viewAny', \App\Models\Customer::class)
                            <a href="{{ route('admin.customer.index') }}" class="btn btn-secondary">
                                @include('components.datatables.button-back')
                            </a>
                        @endcan

                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> <span>{{ __('Save') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </section>
</x-admin-layout>
