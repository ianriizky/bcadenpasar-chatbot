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
        const datatable_url = '{{ route('admin.order.datatable') }}';
        const datatable_columns = [
            { data: 'checkbox', searchable: false, orderable: false, width: '5%' },
            { data: 'detail', searchable: false, orderable: false, width: '5%', class: 'details-control' },
            { data: 'code' },
            { data: 'customer_fullname' },
            { data: 'created_at' },
            { data: 'schedule_date' },
            { data: 'status' },
            { data: 'action', searchable: false, orderable: false, width: '20%' },
        ];
        const datatable_order = [
            [4, 'desc'],
        ];
        @include('components.datatables-id')
    </script>
    <script src="{{ asset('js/datatable-row-child.js') }}"></script>
@endsection

<x-admin-layout>
    <section class="section">
        <div class="section-header">
            <h1>{{ __('List :name', ['name' => __('admin-lang.order')]) }}</h1>

            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <span>{{ __('admin-lang.transaction') }}</span>
                </div>

                <div class="breadcrumb-item">
                    <a href="{{ route('admin.order.index') }}">
                        <i class="fas fa-shopping-cart"></i> <span>{{ __('admin-lang.order') }}</span>
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
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped datatable">
                                        <thead>
                                            <tr>
                                                <th>@include('components.datatables.checkbox-all')</th>
                                                <th></th>
                                                <th>{{ __('Code') }}</th>
                                                <th>{{ __('admin-lang.customer') }}</th>
                                                <th>{{ __('Created At') }}</th>
                                                <th>{{ __('Schedule Date') }}</th>
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
                                    @include('components.datatables.checkbox-delete', ['url' => route('admin.order.destroy-multiple')])
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
</x-admin-layout>
