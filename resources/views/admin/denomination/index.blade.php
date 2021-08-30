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
        const datatable_url = '{{ route('admin.denomination.datatable') }}';
        const datatable_columns = [
            { data: 'checkbox', searchable: false, orderable: false, width: '5%' },
            { data: 'name' },
            { data: 'value' },
            { data: 'type' },
            { data: 'quantity_per_bundle' },
            { data: 'action', searchable: false, orderable: false, width: '26%' },
        ];
        @include('components.datatables-id')
    </script>
    <script src="{{ asset('js/datatable.js') }}"></script>
@endsection

<x-admin-layout>
    <section class="section">
        <div class="section-header">
            <h1>{{ __('List :name', ['name' => __('admin-lang.denomination')]) }}</h1>

            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <span>{{ __('admin-lang.master') }}</span>
                </div>

                <div class="breadcrumb-item">
                    <a href="{{ route('admin.denomination.index') }}">
                        <i class="fas fa-money-bill-wave"></i> <span>{{ __('admin-lang.denomination') }}</span>
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
                                @can('create', \App\Models\Denomination::class)
                                    <a href="{{ route('admin.denomination.create') }}" class="btn btn-success">
                                        <i class="fas fa-plus-square"></i> <span>{{ __('Add :name', ['name' => __('admin-lang.denomination')]) }}</span>
                                    </a>
                                @endcan
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped datatable">
                                        <thead>
                                            <tr>
                                                <th>@include('components.datatables.checkbox-all')</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Value') }}</th>
                                                <th>{{ __('Type') }}</th>
                                                <th>{{ __('Quantity Per Bundle') }}</th>
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
                                    @include('components.datatables.checkbox-delete', ['url' => route('admin.denomination.destroy-multiple')])
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
</x-admin-layout>
