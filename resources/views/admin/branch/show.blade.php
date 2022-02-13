<x-admin-layout>
    <section class="section">
        <div class="section-header">
            <h1>{{ __('Show :name', ['name' => __('admin-lang.branch')]) }}</h1>

            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <span>{{ __('admin-lang.master') }}</span>
                </div>

                <div class="breadcrumb-item">
                    <a href="{{ route('admin.branch.index') }}">
                        <i class="fas fa-building"></i> <span>{{ __('admin-lang.branch') }}</span>
                    </a>
                </div>

                <div class="breadcrumb-item">
                    <a href="{{ route('admin.branch.show', $branch) }}">
                        <i class="fas fa-eye"></i> <span>{{ __('Show :name', ['name' => __('admin-lang.branch')]) }}</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        {{-- name --}}
                        <div class="form-group col-12 col-lg-6">
                            <label for="name">{{ __('Name') }}</label>

                            <p class="form-control-plaintext">{{ $branch->name }}</p>
                        </div>
                        {{-- /.name --}}

                        <div class="col-12 col-lg-6"></div>

                        {{-- address --}}
                        <div class="form-group col-12 col-lg-6">
                            <label for="address">{{ __('Address') }}</label>

                            <p class="form-control-plaintext">{{ $branch->address }}</p>
                        </div>
                        {{-- /.address --}}

                        {{-- address_latitude | address_longitude --}}
                        <div class="form-group col-12 col-lg-6">
                            <label for="address_latitude_longitude">{{ __('Latitude') }} & {{ __('Longitude') }}</label>

                            <p class="form-control-plaintext">{{ $branch->address_latitude }} | {{ $branch->address_longitude }}</p>
                        </div>
                        {{-- /.address_latitude | address_longitude --}}

                        {{-- google_map_url --}}
                        <div class="form-group col-12 col-lg-6">
                            <label for="google_map_url">
                                {{ __('Google Map Address') }}

                                <a href="{{ $branch->google_map_url }}" target="_blank">({{ __('Preview') }})</a>
                            </label>

                            <p class="form-control-plaintext">{{ $branch->getRawOriginal('google_map_url') ?? '-' }}</p>
                        </div>
                        {{-- /.google_map_url --}}
                    </div>

                    @includeWhen($branch->exists, 'components.form-timestamps', ['model' => $branch])
                </div>

                <div class="card-footer">
                    @include('components.datatables.link-back', ['url' => route('admin.branch.index')])

                    @can('update', $branch)
                        @include('components.datatables.link-edit', ['url' => route('admin.branch.edit', $branch)])
                    @endcan
                </div>
            </div>
        </div>
    </section>
</x-admin-layout>
