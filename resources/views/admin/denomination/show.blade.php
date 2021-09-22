<x-admin-layout>
    <section class="section">
        <div class="section-header">
            <h1>{{ __('Show :name', ['name' => __('admin-lang.denomination')]) }}</h1>

            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <span>{{ __('admin-lang.master') }}</span>
                </div>

                <div class="breadcrumb-item">
                    <a href="{{ route('admin.denomination.index') }}">
                        <i class="fas fa-money-bill-wave"></i> <span>{{ __('admin-lang.denomination') }}</span>
                    </a>
                </div>

                <div class="breadcrumb-item">
                    <a href="{{ route('admin.denomination.show', $denomination) }}">
                        <i class="fas fa-eye"></i> <span>{{ __('Show :name', ['name' => __('admin-lang.denomination')]) }}</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        {{-- key --}}
                        <div class="form-group col-12 col-lg-6">
                            <label for="key">{{ __('Key') }}</label>

                            <p class="form-control-plaintext">{{ $denomination->key }}</p>
                        </div>
                        {{-- /.key --}}

                        {{-- name --}}
                        <div class="form-group col-12 col-lg-6">
                            <label for="name">{{ __('Name') }}</label>

                            <p class="form-control-plaintext">{{ $denomination->name }}</p>
                        </div>
                        {{-- /.name --}}

                        {{-- value --}}
                        <div class="form-group col-12 col-lg-6">
                            <label for="value">{{ __('Value') }}</label>

                            <p class="form-control-plaintext">{{ $denomination->value_rupiah }}</p>
                        </div>
                        {{-- /.value --}}

                        {{-- type --}}
                        <div class="form-group col-12 col-lg-6">
                            <label for="type">{{ __('Type') }}</label>

                            <p class="form-control-plaintext">{{ $denomination->type->label }}</p>
                        </div>
                        {{-- /.type --}}

                        {{-- quantity_per_bundle --}}
                        <div class="form-group col-12 col-lg-6">
                            <label for="quantity_per_bundle">{{ __('Quantity Per Bundle') }}</label>

                            <p class="form-control-plaintext">{{ $denomination->quantity_per_bundle }} {{ __('bundle') }}</p>
                        </div>
                        {{-- /.quantity_per_bundle --}}

                        {{-- minimum_order_bundle --}}
                        <div class="form-group col-12 col-lg-3">
                            <label for="minimum_order_bundle">{{ __('Minimum Order Bundle') }}</label>

                            <p class="form-control-plaintext">{{ $denomination->minimum_order_bundle }} {{ __('bundle') }}</p>
                        </div>
                        {{-- /.minimum_order_bundle --}}

                        {{-- maximum_order_bundle --}}
                        <div class="form-group col-12 col-lg-3">
                            <label for="maximum_order_bundle">{{ __('Maximum Order Bundle') }}</label>

                            <p class="form-control-plaintext">{{ $denomination->maximum_order_bundle }} {{ __('bundle') }}</p>
                        </div>
                        {{-- /.maximum_order_bundle --}}

                        {{-- can_order_custom_quantity --}}
                        <div class="form-group col-12 col-lg-6">
                            <label for="can_order_custom_quantity">{{ __('Can Order Custom Quantity') }} <i class="fa fa-question-circle" data-toggle="tooltip" title="{{ __('If the number of orders can be customized, then the minimum and maximum value of the number of orders will following the minimum and maximum value of the number of bundles') }}"></i></label>

                            <p class="form-control-plaintext">{!! $denomination->can_order_custom_quantity_badge !!}</p>
                        </div>
                        {{-- /.can_order_custom_quantity --}}

                        {{-- minimum_order_quantity --}}
                        <div class="form-group col-12 col-lg-3">
                            <label for="minimum_order_quantity">{{ __('Minimum Order Quantity') }}</label>

                            <p class="form-control-plaintext">{{ $denomination->minimum_order_quantity }} {{ Str::lower($denomination->type->label) }}</p>
                        </div>
                        {{-- /.minimum_order_quantity --}}

                        {{-- maximum_order_quantity --}}
                        <div class="form-group col-12 col-lg-3">
                            <label for="maximum_order_quantity">{{ __('Maximum Order Quantity') }}</label>

                            <p class="form-control-plaintext">{{ $denomination->maximum_order_quantity }} {{ Str::lower($denomination->type->label) }}</p>
                        </div>
                        {{-- /.maximum_order_quantity --}}

                        {{-- is_visible --}}
                        <div class="form-group col-12 col-lg-12">
                            <label for="is_visible">{{ __('Visible') }}</label>

                            <p class="form-control-plaintext">{!! $denomination->is_visible_badge !!}</p>
                        </div>
                        {{-- /.is_visible --}}

                        {{-- image_preview --}}
                        <div class="form-group col-12 col-lg-6">
                            <label for="image">{{ __('Image') }}</label>

                            <img src="{{ $denomination->image }}" id="image_preview" class="w-100" alt="image preview">
                        </div>
                        {{-- /.image_preview --}}
                    </div>

                    @include('components.form-timestamps', ['model' => $denomination])
                </div>

                <div class="card-footer">
                    @include('components.datatables.link-back', ['url' => route('admin.denomination.index')])

                    @can('update', $denomination)
                        @include('components.datatables.link-edit', ['url' => route('admin.denomination.edit', $denomination)])
                    @endcan
                </div>
            </div>
        </div>
    </section>
</x-admin-layout>
