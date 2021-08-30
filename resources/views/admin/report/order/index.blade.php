@section('style')
    <link rel="stylesheet" href="{{ mix('node_modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ mix('node_modules/bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection

@section('script')
    <script src="{{ mix('node_modules/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ mix('node_modules/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        const datatable_ajax = {
            url: '{{ route('admin.report.order.datatable') }}',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            data: {
                daterange: function () {
                    return $('input#daterange').val();
                },
            },
        };
        const datatable_columns = [
            { render: function (data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; } },
            { data: 'code' },
            { data: 'customer_fullname' },
            { data: 'created_at' },
            { data: 'schedule_date' },
            { data: 'status' },
        ];
        @include('components.datatables-id')
    </script>
    <script src="{{ asset('js/datatable.js') }}"></script>
    <script src="{{ mix('node_modules/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.daterange').daterangepicker({
                locale: { format: 'YYYY-MM-DD' },
                drops: 'down',
                opens: 'right',
                startDate: moment().startOf('month'),
                endDate: moment().endOf('month'),
                autoApply: true,
            });

            $('button#search-report').click(function (event) {
                datatable.ajax.reload();
            });
        });
    </script>
@endsection

<x-admin-layout>
    <section class="section">
        <div class="section-header">
            <h1>{{ __('admin-lang.report-order') }}</h1>

            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <span>{{ __('admin-lang.report') }}</span>
                </div>

                <div class="breadcrumb-item">
                    <a href="{{ route('admin.report.order.index') }}">
                        <i class="fas fa-file-alt"></i> <span>{{ __('admin-lang.report-order') }}</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="post" class="row mb-3">
                                @csrf

                                <div class="form-group col-12 col-xl-6">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar"></i>
                                            </div>
                                        </div>

                                        <input type="text" name="daterange" id="daterange" class="form-control daterange">

                                        <div class="input-group-append">
                                            <button type="button" id="search-report" class="btn btn-primary">
                                                <i class="fa fa-search"></i> <span class="d-none d-xl-inline">{{ __('Search') }}</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-xl-3">
                                    <button type="submit" formaction="{{ route('admin.report.order.export') }}" class="btn btn-block btn-round btn-success">
                                        <i class="fa fa-download"></i> <span class="d-none d-xl-inline">{{ __('Download') }} {{ __('admin-lang.report-order') }}</span>
                                    </button>
                                </div>
                            </form>

                            <div class="table-responsive">
                                <table class="table table-striped datatable">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>{{ __('Code') }}</th>
                                            <th>{{ __('admin-lang.customer') }}</th>
                                            <th>{{ __('Created At') }}</th>
                                            <th>{{ __('Schedule Date') }}</th>
                                            <th>Status</th>
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
