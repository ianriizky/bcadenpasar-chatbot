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
        $(document).ready(function () {
            $('.datatable').dataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('role.datatable') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                },
                order: [
                    [1, 'asc'],
                ],
                columns: [
                    { data: 'checkbox', searchable: false, orderable: false, width: '5%' },
                    { data: 'name', searchable: true },
                    { data: 'guard_name', searchable: true},
                    { data: 'action', searchable: false, orderable: false },
                ],
                language: {
                    url: '{{ asset(sprintf('node_modules/datatables.net-plugins/i18n/%s.json', App::getLocale())) }}',
                },
            });
        });
    </script>
@endsection

<x-app-layout>
    <section class="section">
        <div class="section-header">
            <h1>{{ __('List :name', ['name' => __('admin-lang.role')]) }}</h1>

            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active">
                    <a href="{{ route('dashboard') }}">
                        <i class="fas fa-fire"></i> <span>{{ __('Dashboard') }}</span>
                    </a>
                </div>

                <div class="breadcrumb-item">
                    <a href="{{ route('role.index') }}">
                        <i class="fas fa-cog"></i> <span>{{ __('admin-lang.role') }}</span>
                    </a>
                </div>

                <div class="breadcrumb-item">
                    <i class="fas fa-list"></i> <span>{{ __('List :name', ['name' => __('admin-lang.role')]) }}</span>
                </div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <a href="{{ route('role.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus-square"></i> <span>{{ __('Add :name', ['name' => __('admin-lang.role')]) }}</span>
                            </a>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped datatable">
                                    <thead>
                                        <tr>
                                            <th>@include('components.datatables.checkbox-all')</th>
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('Guard Name') }}</th>
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
</x-app-layout>