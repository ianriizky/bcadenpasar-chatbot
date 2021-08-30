@section('pre-style')
    <link rel="stylesheet" href="{{ mix('node_modules/select2/dist/css/select2.min.css') }}">
@endsection

@section('style')
    <link rel="stylesheet" href="{{ mix('node_modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ mix('node_modules/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}">
@endsection

@section('script')
    <script src="{{ mix('node_modules/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ mix('node_modules/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ mix('node_modules/datatables.net-select-bs4/js/select.bootstrap4.min.js') }}"></script>
    <script src="{{ mix('node_modules/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/geolocation.js') }}"></script>
    <script src="{{ asset('js/custom-file-input.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2();

            @include('components.select2-change', ['olds' => Arr::except(old() ?: $order, '_token')])
            @include('components.datatables-id')

            $('.datatable').DataTable({
                paging: false,
                searching: false,
                bInfo: false,
                columnDefs: [
                    { orderable: false, targets: -1 },
                ],
                language: {
                    url: datatable_language_url,
                },
            });
        });
    </script>
@endsection

<x-admin-layout>
    <section class="section">
        <div class="section-header">
            <h1>{{ __('Show :name', ['name' => __('admin-lang.order')]) }}</h1>

            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <span>{{ __('admin-lang.transaction') }}</span>
                </div>

                <div class="breadcrumb-item">
                    <a href="{{ route('admin.order.index') }}">
                        <i class="fas fa-shopping-cart"></i> <span>{{ __('admin-lang.order') }}</span>
                    </a>
                </div>

                <div class="breadcrumb-item">
                    <a href="{{ route('admin.order.show', $order) }}">
                        <i class="fas fa-eye"></i> <span>{{ __('Show :name', ['name' => __('admin-lang.order')]) }}</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        {{-- code --}}
                        <div class="form-group col-12 col-lg-6">
                            <label for="code">{{ __('Code') }}</label>

                            <p class="form-control-plaintext">{{ $order->code }}</p>
                        </div>
                        {{-- /.code --}}

                        <div class="col-12 col-lg-6"></div>

                        {{-- branch_id --}}
                        <div class="form-group col-12 col-lg-6">
                            <label for="branch_id">{{ __('admin-lang.branch') }}</label>

                            <p class="form-control-plaintext">
                                @if ($order->branch)
                                    @can('view', $order->branch)
                                        <a href="{{ route('admin.branch.show', $order->branch) }}">{{ $order->branch->name }}</a>
                                    @else
                                        <a href="{{ $order->branch->google_map_url }}" target="_blank">{{ $order->branch->name }}</a>
                                    @endcan
                                @else
                                    {{ __('Unscheduled') }}
                                @endif
                            </p>
                        </div>
                        {{-- /.branch_id --}}

                        {{-- user_id --}}
                        <div class="form-group col-12 col-lg-6">
                            <label for="user_id">{{ __('admin-lang.user') }}</label>

                            <p class="form-control-plaintext">
                                @if ($order->user)
                                    @can('view', $order->user)
                                        <a href="{{ route('admin.user.show', $order->user) }}">{{ $order->user->fullname }}</a>
                                    @else
                                        <p class="form-control-plaintext">{{ $order->user->fullname }}</p>
                                    @endcan
                                @else
                                    {{ __('Unscheduled') }}
                                @endif
                            </p>
                        </div>
                        {{-- /.user_id --}}

                        {{-- items --}}
                        <div class="form-group col-12">
                            <label for="items"> {{ __(':resource Details', ['resource' => __('admin-lang.order')]) }} </label>

                            @include('admin.item.index', ['items' => $order->items])
                        </div>

                        {{-- statuses --}}
                        <div class="form-group col-12">
                            <label for="statuses">{{ __('Order Status') }}</label>

                            @include('admin.order_status.index', ['statuses' => $order->statuses])
                        </div>
                        {{-- /.statuses --}}

                        {{-- item_total_bundle_quantity --}}
                        <div class="form-group col-12 col-lg-6">
                            <label for="item_total_bundle_quantity">{{ __(':amount Total', ['amount' => __('Bundle Quantity')]) }}</label>

                            <p class="form-control-plaintext">{{ $order->item_total_bundle_quantity }} {{ __('bundle') }}</p>
                        </div>
                        {{-- /.item_total_bundle_quantity --}}

                        {{-- item_total --}}
                        <div class="form-group col-12 col-lg-6">
                            <label for="item_total">{{ __(':amount Total', ['amount' => __('admin-lang.order')]) }}</label>

                            <p class="form-control-plaintext">{{ format_rupiah($order->item_total) }}</p>
                        </div>
                        {{-- /.item_total --}}
                    </div>

                    @includeWhen($order->exists, 'components.form-timestamps', ['model' => $order])
                </div>

                <form method="post">
                    @csrf

                    <div class="card-footer">
                        @can('viewAny', \App\Models\Order::class)
                            <a href="{{ route('admin.order.index') }}" class="btn btn-secondary">
                                @include('components.datatables.button-back')
                            </a>
                        @endcan

                        @can('delete', $order)
                            <button type="submit"
                                formaction="{{ route('admin.order.destroy', $order) }}"
                                name="_method" value="DELETE"
                                onclick="return (confirm('{{ __('Are you sure you want to delete this data?') }}'))"
                                class="btn btn-danger">
                                <i class="fa fa-trash"></i> <span>{{ __('Delete') }}</span>
                            </button>
                        @endcan
                    </div>
                </form>
            </div>
        </div>
    </section>
</x-admin-layout>
