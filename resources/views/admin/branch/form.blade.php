@section('script')
    <script src="{{ asset('js/geolocation.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('button#find-location').click(async function () {
                const previousHtml = $(this).html();

                $(this).attr('disabled', 'disabled')
                $(this).html('<i class="fa fa-spinner fa-spin"></i> <span>{{ __('Please wait') }}</span>');

                try {
                    const position = await geoFindMe();

                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;

                    $('input#address_latitude').val(latitude);
                    $('input#address_longitude').val(longitude);
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

<x-app-layout>
    <section class="section">
        <div class="section-header">
            <h1>{{ $title }}</h1>

            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-fire"></i> <span>{{ __('Dashboard') }}</span>
                    </a>
                </div>

                <div class="breadcrumb-item">
                    <a href="{{ route('admin.branch.index') }}">
                        <i class="fas fa-building"></i> <span>{{ __('admin-lang.branch') }}</span>
                    </a>
                </div>

                <div class="breadcrumb-item">
                    <a href="{{ route('admin.branch.create') }}">
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
                            {{-- name --}}
                            <div class="form-group col-12 col-lg-6">
                                <label for="name">{{ __('Name') }}<span class="text-danger">*</span></label>

                                <input type="text"
                                    name="name"
                                    id="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $branch->name) }}"
                                    required
                                    autofocus>

                                <x-invalid-feedback :name="'name'"/>
                            </div>
                            {{-- /.name --}}

                            <div class="col-12 col-lg-6"></div>

                            {{-- address --}}
                            <div class="form-group col-12 col-lg-6">
                                <label for="address">{{ __('Address') }}<span class="text-danger">*</span></label>

                                <textarea name="address"
                                    id="address"
                                    class="form-control @error('address') is-invalid @enderror"
                                    style="resize: vertical; height: auto;"
                                    required>{{ old('address', $branch->address) }}</textarea>

                                <x-invalid-feedback :name="'address'"/>
                            </div>
                            {{-- /.address --}}

                            {{-- address_latitude | address_longitude --}}
                            <div class="form-group col-12 col-lg-6">
                                <label for="address_latitude_longitude">{{ __('Latitude') }} & {{ __('Longitude') }}<span class="text-danger">*</span></label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <button type="button" class="btn btn-primary" id="find-location">
                                            <i class="fa fa-location-arrow"></i> <span>{{ __('Send My Location') }}</span>
                                        </button>
                                    </div>

                                    <input type="text"
                                        name="address_latitude"
                                        id="address_latitude"
                                        class="form-control @error('address_latitude') is-invalid @enderror"
                                        value="{{ old('address_latitude', $branch->address_latitude) }}"
                                        placeholder="{{ __('Type :field', ['field' => __('Latitude')]) }}"
                                        required>

                                    <input type="text"
                                        name="address_longitude"
                                        id="address_longitude"
                                        class="form-control @error('address_longitude') is-invalid @enderror"
                                        value="{{ old('address_longitude', $branch->address_longitude) }}"
                                        placeholder="{{ __('Type :field', ['field' => __('Longitude')]) }}"
                                        required>
                                </div>

                                <x-invalid-feedback :name="'address_latitude'"/>
                                <x-invalid-feedback :name="'address_longitude'"/>
                            </div>
                            {{-- /.address_latitude | address_longitude --}}

                            {{-- google_map_url --}}
                            <div class="form-group col-12 col-lg-6">
                                <label for="google_map_url">Google Map URL</label>

                                <textarea name="google_map_url"
                                    id="google_map_url"
                                    class="form-control @error('google_map_url') is-invalid @enderror"
                                    style="resize: vertical; height: auto;">{{ old('google_map_url', $branch->getRawOriginal('google_map_url')) }}</textarea>

                                <x-invalid-feedback :name="'google_map_url'"/>

                                <small id="google_map_url_hint" class="form-text text-muted">
                                    {{ __('If this column is left empty, then the Google Map URL will be generated automatically following the latitude and longitude values') }}
                                    <br>
                                    @if ($branch->exists && is_null($branch->getRawOriginal('google_map_url')))
                                        <a href="{{ $branch->google_map_url }}" target="_blank">{{ $branch->google_map_url }}</a>
                                    @endif
                                </small>
                            </div>
                            {{-- /.google_map_url --}}
                        </div>

                        @includeWhen($branch->exists, 'components.form-timestamps', ['model' => $branch])
                    </div>

                    <div class="card-footer">
                        <a href="{{ route('admin.branch.index') }}" class="btn btn-secondary">
                            <i class="fa fa-chevron-left"></i> <span>{{ __('Go back') }}</span>
                        </a>

                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> <span>{{ __('Save') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </section>
</x-app-layout>
