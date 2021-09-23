@section('pre-style')
    <link rel="stylesheet" href="{{ mix('node_modules/select2/dist/css/select2.min.css') }}">
@endsection

@section('script')
    <script src="{{ mix('node_modules/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/custom-file-input.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2();

            @include('components.select2-change', ['olds' => Arr::except(old() ?: $denomination, '_token')])

            $('select#type').change(function () {
                switch (this.value) {
                    case @json(App\Enum\DenominationType::coin()):
                        $('div#minimum_order_quantity_text_append').text('{{ Str::lower(App\Enum\DenominationType::coin()->label) }}');
                        $('div#maximum_order_quantity_text_append').text('{{ Str::lower(App\Enum\DenominationType::coin()->label) }}');
                        break;

                    case @json(App\Enum\DenominationType::banknote()):
                        $('div#minimum_order_quantity_text_append').text('{{ Str::lower(App\Enum\DenominationType::banknote()->label) }}');
                        $('div#maximum_order_quantity_text_append').text('{{ Str::lower(App\Enum\DenominationType::banknote()->label) }}');
                        break;

                    default:
                    $('div#minimum_order_quantity_text_append').text(null);
                    $('div#maximum_order_quantity_text_append').text(null);
                        break;
                }
            }).trigger('change');

            $('input[name=can_order_custom_quantity]').change(function () {
                if (this.value == 1) {
                    $('input#minimum_order_quantity').removeAttr('disabled');
                    $('input#maximum_order_quantity').removeAttr('disabled');
                } else {
                    $('input#minimum_order_quantity').attr('disabled', 'disabled');
                    $('input#maximum_order_quantity').attr('disabled', 'disabled');

                    $('.minimum_order_quantity_listener').trigger('change');
                    $('.maximum_order_quantity_listener').trigger('change');
                }
            }).filter(':checked').trigger('change');

            $('.minimum_order_quantity_listener').change(function () {
                if ($('input[name=can_order_custom_quantity]:checked').val() == 0) {
                    $('input#minimum_order_quantity').val(
                        $('input#quantity_per_bundle').val() * $('input#minimum_order_bundle').val()
                    );
                }
            });

            $('.maximum_order_quantity_listener').change(function () {
                if ($('input[name=can_order_custom_quantity]:checked').val() == 0) {
                    $('input#maximum_order_quantity').val(
                        $('input#quantity_per_bundle').val() * $('input#maximum_order_bundle').val()
                    );
                }
            });
        });
    </script>
@endsection

<x-admin-layout>
    <section class="section">
        <div class="section-header">
            <h1>{{ $title }}</h1>

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
                    <a href="{{ $url }}">
                        <i class="fas {{ $icon }}"></i> <span>{{ $title }}</span>
                    </a>
                </div>
            </div>
        </div>

        <form method="post" enctype="multipart/form-data">
            @csrf

            <div class="section-body">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            {{-- code --}}
                            <div class="form-group col-12 col-lg-6">
                                <label for="code">{{ __('Code') }}<span class="text-danger">*</span></label>

                                <input type="text"
                                    name="code"
                                    id="code"
                                    class="form-control @error('code') is-invalid @enderror"
                                    value="{{ old('code', $denomination->code) }}"
                                    required
                                    autofocus>

                                <x-invalid-feedback :name="'code'"/>
                            </div>
                            {{-- /.code --}}

                            {{-- name --}}
                            <div class="form-group col-12 col-lg-6">
                                <label for="name">{{ __('Name') }}<span class="text-danger">*</span></label>

                                <input type="text"
                                    name="name"
                                    id="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $denomination->name) }}"
                                    required
                                    autofocus>

                                <x-invalid-feedback :name="'name'"/>
                            </div>
                            {{-- /.name --}}

                            {{-- value --}}
                            <div class="form-group col-12 col-lg-6">
                                <label for="value">{{ __('Value') }}<span class="text-danger">*</span></label>

                                <input type="number"
                                    name="value"
                                    id="value"
                                    class="form-control @error('value') is-invalid @enderror"
                                    value="{{ old('value', $denomination->value) }}"
                                    min="0" step="100"
                                    required
                                    autofocus>

                                <x-invalid-feedback :name="'value'"/>
                            </div>
                            {{-- /.value --}}

                            {{-- type --}}
                            <div class="form-group col-12 col-lg-6">
                                <label for="type">{{ __('Type') }}<span class="text-danger">*</span></label>

                                <select name="type"
                                    id="type"
                                    class="form-control select2 @error('type') is-invalid @enderror"
                                    data-placeholder="--{{ __('Choose :field', ['field' => __('Type') ]) }}--"
                                    data-allow-clear="true"
                                    required>
                                    @foreach (EnumDenominationType::toArray() as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>

                                <x-invalid-feedback :name="'type'"/>
                            </div>
                            {{-- /.type --}}

                            {{-- quantity_per_bundle --}}
                            <div class="form-group col-12 col-lg-6">
                                <label for="quantity_per_bundle">{{ __('Quantity Per Bundle') }}<span class="text-danger">*</span></label>

                                <div class="input-group">
                                    <input type="number"
                                        name="quantity_per_bundle"
                                        id="quantity_per_bundle"
                                        class="form-control minimum_order_quantity_listener maximum_order_quantity_listener @error('quantity_per_bundle') is-invalid @enderror"
                                        value="{{ old('quantity_per_bundle', $denomination->quantity_per_bundle) }}"
                                        min="0"
                                        required
                                        autofocus>

                                    <div class="input-group-append">
                                        <div class="input-group-text">{{ __('bundle') }}</div>
                                    </div>

                                    <x-invalid-feedback :name="'quantity_per_bundle'"/>
                                </div>
                            </div>
                            {{-- /.quantity_per_bundle --}}

                            {{-- minimum_order_bundle --}}
                            <div class="form-group col-12 col-lg-3">
                                <label for="minimum_order_bundle">{{ __('Minimum Order Bundle') }}<span class="text-danger">*</span></label>

                                <div class="input-group">
                                    <input type="number"
                                        name="minimum_order_bundle"
                                        id="minimum_order_bundle"
                                        class="form-control minimum_order_quantity_listener @error('minimum_order_bundle') is-invalid @enderror"
                                        value="{{ old('minimum_order_bundle', $denomination->minimum_order_bundle) }}"
                                        min="0"
                                        required
                                        autofocus>

                                    <div class="input-group-append">
                                        <div class="input-group-text">{{ __('bundle') }}</div>
                                    </div>

                                    <x-invalid-feedback :name="'minimum_order_bundle'"/>
                                </div>
                            </div>
                            {{-- /.minimum_order_bundle --}}

                            {{-- maximum_order_bundle --}}
                            <div class="form-group col-12 col-lg-3">
                                <label for="maximum_order_bundle">{{ __('Maximum Order Bundle') }}<span class="text-danger">*</span></label>

                                <div class="input-group">
                                    <input type="number"
                                        name="maximum_order_bundle"
                                        id="maximum_order_bundle"
                                        class="form-control maximum_order_quantity_listener @error('maximum_order_bundle') is-invalid @enderror"
                                        value="{{ old('maximum_order_bundle', $denomination->maximum_order_bundle) }}"
                                        min="0"
                                        required
                                        autofocus>

                                    <div class="input-group-append">
                                        <div class="input-group-text">{{ __('bundle') }}</div>
                                    </div>

                                    <x-invalid-feedback :name="'maximum_order_bundle'"/>
                                </div>
                            </div>
                            {{-- /.maximum_order_bundle --}}

                            {{-- can_order_custom_quantity --}}
                            <div class="form-group col-12 col-lg-6">
                                <label for="can_order_custom_quantity" class="d-block">{{ __('Can Order Custom Quantity') }}<span class="text-danger">*</span> <i class="fa fa-question-circle" data-toggle="tooltip" title="{{ __('If the number of orders can be customized, then the minimum and maximum value of the number of orders will following the minimum and maximum value of the number of bundles') }}"></i></label>

                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio"
                                        name="can_order_custom_quantity"
                                        id="can_order_custom_quantity_true"
                                        value="1"
                                        @if (old('can_order_custom_quantity', $denomination->can_order_custom_quantity)) checked @endif
                                        class="custom-control-input">

                                    <label class="custom-control-label" for="can_order_custom_quantity_true">{{ __('Yes') }}</label>
                                </div>

                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio"
                                        name="can_order_custom_quantity"
                                        id="can_order_custom_quantity_false"
                                        value="0"
                                        @unless (old('can_order_custom_quantity', $denomination->can_order_custom_quantity)) checked @endunless
                                        class="custom-control-input">

                                    <label class="custom-control-label" for="can_order_custom_quantity_false">{{ __('No') }}</label>
                                </div>

                                <x-invalid-feedback :name="'can_order_custom_quantity'"/>
                            </div>
                            {{-- /.can_order_custom_quantity --}}

                            {{-- minimum_order_quantity --}}
                            <div class="form-group col-12 col-lg-3">
                                <label for="minimum_order_quantity">{{ __('Minimum Order Quantity') }}</label>

                                <div class="input-group">
                                    <input type="number"
                                        name="minimum_order_quantity"
                                        id="minimum_order_quantity"
                                        class="form-control @error('minimum_order_quantity') is-invalid @enderror"
                                        value="{{ old('minimum_order_quantity', $denomination->minimum_order_quantity) }}"
                                        min="0"
                                        required
                                        autofocus>

                                    <div class="input-group-append">
                                        <div id="minimum_order_quantity_text_append" class="input-group-text"></div>
                                    </div>

                                    <x-invalid-feedback :name="'minimum_order_quantity'"/>
                                </div>
                            </div>
                            {{-- /.minimum_order_quantity --}}

                            {{-- maximum_order_quantity --}}
                            <div class="form-group col-12 col-lg-3">
                                <label for="maximum_order_quantity">{{ __('Maximum Order Quantity') }}</label>

                                <div class="input-group">
                                    <input type="number"
                                        name="maximum_order_quantity"
                                        id="maximum_order_quantity"
                                        class="form-control @error('maximum_order_quantity') is-invalid @enderror"
                                        value="{{ old('maximum_order_quantity', $denomination->maximum_order_quantity) }}"
                                        min="0"
                                        required
                                        autofocus>

                                    <div class="input-group-append">
                                        <div id="maximum_order_quantity_text_append" class="input-group-text"></div>
                                    </div>

                                    <x-invalid-feedback :name="'maximum_order_quantity'"/>
                                </div>
                            </div>
                            {{-- /.maximum_order_quantity --}}

                            {{-- is_visible --}}
                            <div class="form-group col-12 col-lg-12">
                                <label for="is_visible" class="d-block">{{ __('Visible') }}<span class="text-danger">*</span></label>

                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio"
                                        name="is_visible"
                                        id="is_visible_true"
                                        value="1"
                                        @if (old('is_visible', $denomination->is_visible)) checked @endif
                                        class="custom-control-input">

                                    <label class="custom-control-label" for="is_visible_true">{{ __('Yes') }}</label>
                                </div>

                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio"
                                        name="is_visible"
                                        id="is_visible_false"
                                        value="0"
                                        @unless (old('is_visible', $denomination->is_visible)) checked @endunless
                                        class="custom-control-input">

                                    <label class="custom-control-label" for="is_visible_false">{{ __('No') }}</label>
                                </div>

                                <x-invalid-feedback :name="'is_visible'"/>
                            </div>
                            {{-- /.is_visible --}}

                            {{-- image --}}
                            <div class="form-group col-12 col-lg-6">
                                @if ($denomination->exists)
                                    <label for="image">{{ __('Update :name', ['name' => __('Image')]) }}</label>
                                @else
                                    <label for="image">{{ __('Image') }}</label>
                                @endif

                                <div class="custom-file">
                                    <label class="custom-file-label" for="image">{{ __('Choose file') }}</label>

                                    <input type="file"
                                        name="image"
                                        id="image"
                                        class="custom-file-input"
                                        data-preview="#image_preview"
                                        accept="image/*">
                                </div>
                            </div>
                            {{-- /.image --}}

                            <div class="col-12 col-lg-6"></div>

                            {{-- image_preview --}}
                            <div class="col-12 col-lg-6">
                                @if ($denomination->exists && $denomination->getRawOriginal('image'))
                                    <button type="submit"
                                        class="btn btn-danger mb-3"
                                        formaction="{{ route('admin.denomination.destroy-image', $denomination) }}"
                                        name="_method"
                                        value="DELETE"
                                        onclick="return confirm('{{ __('Are you sure you want to delete this data?') }}')">
                                        <i class="fa fa-trash"></i> <span>{{ __('Delete :name', ['name' => __('Image')]) }}</span>
                                    </button>
                                @endif

                                <img src="{{ $denomination->image }}" id="image_preview" class="w-100" alt="image preview">
                            </div>
                            {{-- /.image_preview --}}
                        </div>

                        @includeWhen($denomination->exists, 'components.form-timestamps', ['model' => $denomination])
                    </div>

                    <div class="card-footer">
                        @can('viewAny', \App\Models\Denomination::class)
                            <a href="{{ route('admin.denomination.index') }}" class="btn btn-secondary">
                                @include('components.datatables.button-back')
                            </a>
                        @endcan

                        <button type="submit"
                            formaction="{{ $action }}"
                            @isset($method) name="_method" value="{{ $method }}" @endisset
                            class="btn btn-primary">
                            <i class="fa fa-save"></i> <span>{{ __('Save') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </section>
</x-admin-layout>
