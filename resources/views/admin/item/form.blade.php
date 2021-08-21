@section('pre-style')
    <link rel="stylesheet" href="{{ mix('node_modules/select2/dist/css/select2.min.css') }}">
@endsection

@section('script')
    <script src="{{ mix('node_modules/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/custom-file-input.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2();

            @include('components.select2-change', ['olds' => Arr::except(old() ?: $item, '_token')])
        });
    </script>
@endsection

<x-admin-layout>
    <section class="section">
        <div class="section-header">
            <h1>{{ $title }}</h1>

            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ route('admin.order.index') }}">
                        <i class="fas fa-shopping-cart"></i> <span>{{ __('admin-lang.order') }}</span>
                    </a>
                </div>

                <div class="breadcrumb-item">
                    <a href="{{ route('admin.order.show', $item->order) }}">
                        <i class="fas fa-edit"></i> <span>{{ __('Edit :name', ['name' => __('admin-lang.order')]) }} {{ $item->order->code }}</span>
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
                            {{-- denomination_id --}}
                            <div class="form-group col-12 col-lg-6">
                                <label for="denomination_id">{{ __('admin-lang.denomination') }}<span class="text-danger">*</span></label>

                                <select name="denomination_id"
                                    id="denomination_id"
                                    class="form-control select2 @error('denomination_id') is-invalid @enderror"
                                    data-placeholder="--{{ __('Choose :field', ['field' => __('admin-lang.denomination') ]) }}--"
                                    data-allow-clear="true"
                                    required
                                    autofocus>
                                    @foreach (\App\Models\Denomination::pluck('name', 'id') as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>

                                <x-invalid-feedback :name="'denomination_id'"/>
                            </div>
                            {{-- /.denomination_id --}}

                            <div class="col-12 col-lg-6"></div>

                            {{-- bundle_quantity --}}
                            <div class="form-group col-12 col-lg-3">
                                <label for="bundle_quantity">{{ __('Bundle Quantity') }}<span class="text-danger">*</span></label>

                                <div class="input-group">
                                    <input type="number" step="1"
                                        @if ($item->denomination)
                                            min="{{ $item->denomination->minimum_order_bundle }}" max="{{ $item->denomination->maximum_order_bundle }}"
                                        @else
                                            min="1"
                                        @endif
                                        name="bundle_quantity"
                                        id="bundle_quantity"
                                        class="form-control @error('bundle_quantity') is-invalid @enderror"
                                        value="{{ old('bundle_quantity', $item->bundle_quantity) }}"
                                        required>

                                    <div class="input-group-append">
                                        <div class="input-group-text">{{ __('bundle') }}</div>
                                    </div>

                                    <x-invalid-feedback :name="'bundle_quantity'"/>
                                </div>
                            </div>
                            {{-- /.bundle_quantity --}}

                            @if ($item->denomination)
                                {{-- quantity_per_bundle --}}
                                <div class="form-group col-12 col-lg-3">
                                    <label for="quantity_per_bundle">{{ __('Quantity Per Bundle') }}<span class="text-danger">*</span></label>

                                    <div class="input-group">
                                        <input type="number"
                                            min="1" step="1"
                                            name="quantity_per_bundle"
                                            id="quantity_per_bundle"
                                            class="form-control @error('quantity_per_bundle') is-invalid @enderror"
                                            value="{{ old('quantity_per_bundle', $item->quantity_per_bundle) }}"
                                            required>

                                        <div class="input-group-append">
                                            <div class="input-group-text">{{ Str::lower($item->denomination->type->label) }}</div>
                                        </div>

                                        <x-invalid-feedback :name="'quantity_per_bundle'"/>
                                    </div>
                                </div>
                                {{-- /.quantity_per_bundle --}}
                            @endif
                        </div>

                        @includeWhen($item->exists, 'components.form-timestamps', ['model' => $item])
                    </div>

                    <div class="card-footer">
                        @can('view', $item->order)
                            <a href="{{ route('admin.order.show', $item->order) }}" class="btn btn-secondary">
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
