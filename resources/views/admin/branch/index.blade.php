@section('style')
    <link rel="stylesheet" href="{{ asset('node_modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('node_modules/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}">
@endsection

@section('script')
    <script src="{{ asset('node_modules/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('node_modules/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('node_modules/datatables.net-select-bs4/js/select.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/stisla/data-checkboxes.js') }}"></script>
    <script>
        const datatable_url = '{{ route('admin.branch.datatable') }}';
        const datatable_columns = [
            { data: 'checkbox', searchable: false, orderable: false, width: '5%' },
            { data: 'name' },
            { data: 'address' },
            { data: 'google_map_url' },
            { data: 'action', searchable: false, orderable: false, width: '20%' },
        ];
        const datatable_language_url = '{{ asset(sprintf('node_modules/datatables.net-plugins/i18n/%s.json', App::getLocale())) }}';
    </script>
    <script src="{{ asset('js/datatable.js') }}"></script>
@endsection

<x-admin-layout>
    <section class="section">
        <div class="section-header">
            <h1>{{ __('List :name', ['name' => __('admin-lang.branch')]) }}</h1>

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
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <a href="{{ route('admin.branch.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus-square"></i> <span>{{ __('Add :name', ['name' => __('admin-lang.branch')]) }}</span>
                            </a>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped datatable">
                                    <thead>
                                        <tr>
                                            <th>@include('components.datatables.checkbox-all')</th>
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('Address') }}</th>
                                            <th>{{ __('Google Map Address') }}</th>
                                            <th>{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-admin-layout>
