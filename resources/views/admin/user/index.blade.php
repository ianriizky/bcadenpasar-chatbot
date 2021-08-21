@section('style')
    <link rel="stylesheet" href="{{ mix('node_modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ mix('node_modules/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}">
@endsection

@section('script')
    <script src="{{ mix('node_modules/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ mix('node_modules/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ mix('node_modules/datatables.net-select-bs4/js/select.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/stisla/data-checkboxes.js') }}"></script>
    <script>
        const datatable_url = '{{ route('admin.user.datatable') }}';
        const datatable_columns = [
            { data: 'checkbox', searchable: false, orderable: false, width: '5%' },
            { data: 'branch_name' },
            { data: 'username' },
            { data: 'fullname' },
            { data: 'email' },
            { data: 'is_active' },
            { data: 'action', searchable: false, orderable: false, width: '30%' },
        ];
        @include('components.datatables-id')
    </script>
    <script src="{{ asset('js/datatable.js') }}"></script>
@endsection

<x-admin-layout>
    <section class="section">
        <div class="section-header">
            <h1>{{ __('List :name', ['name' => __('admin-lang.user')]) }}</h1>

            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ route('admin.user.index') }}">
                        <i class="fas fa-id-badge"></i> <span>{{ __('admin-lang.user') }}</span>
                    </a>
                </div>
            </div>
        </div>

        <form method="post">
            @csrf

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                @can('create', \App\Models\User::class)
                                    <a href="{{ route('admin.user.create') }}" class="btn btn-success">
                                        <i class="fas fa-plus-square"></i> <span>{{ __('Add :name', ['name' => __('admin-lang.user')]) }}</span>
                                    </a>
                                @endcan
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped datatable">
                                        <thead>
                                            <tr>
                                                <th>@include('components.datatables.checkbox-all')</th>
                                                <th>{{ __('admin-lang.branch') }}</th>
                                                <th>Username</th>
                                                <th>{{ __('Full name') }}</th>
                                                <th>{{ __('Email Address') }}</th>
                                                <th>Status</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>

                            <div class="card-footer">
                                {{ __('Selected') }} (<span id="checkbox-selected-display">0</span>)

                                <br>

                                <div class="btn-group">
                                    @include('components.datatables.checkbox-delete', ['url' => route('admin.user.destroy-multiple')])
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
</x-admin-layout>
