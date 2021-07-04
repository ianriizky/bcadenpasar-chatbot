@section('pre-style')
    <link rel="stylesheet" href="{{ asset('node_modules/select2/dist/css/select2.min.css') }}">
@endsection

@section('script')
    <script src="{{ asset('node_modules/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/custom-file-input.js') }}"></script>

    <script>
        $(document).ready(function () {
            $('.select2').select2();

            const olds = @json(Arr::except(old() ?: $denomination, '_token'));

            $('select.select2').each(function (index) {
                name = $(this).attr('name')
                old = name in olds ? olds[name] : null;

                $(this).val(old).trigger('change');
            });
        });
    </script>
@endsection

<x-app-layout>
    <section class="section">
        <div class="section-header">
            <h1>{{ $title }}</h1>

            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-fire"></i> <span>{{ __('Dashboard') }}</span>
                    </a>
                </div>

                <div class="breadcrumb-item">
                    <a href="{{ route('admin.denomination.index') }}">
                        <i class="fas fa-money-bill-wave"></i> <span>{{ __('admin-lang.denomination') }}</span>
                    </a>
                </div>

                <div class="breadcrumb-item">
                    <a href="{{ route('admin.denomination.create') }}">
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
                            {{-- name --}}
                            <div class="form-group col-12 col-lg-6">
                                <label for="name">{{ __('Name') }}</label>

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

                            <div class="col-12 col-lg-6"></div>

                            {{-- value --}}
                            <div class="form-group col-12 col-lg-6">
                                <label for="value">{{ __('Value') }}</label>

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
                                <label for="type">{{ __('Type') }}</label>

                                <select name="type"
                                    id="type"
                                    class="form-control select2 @error('type') is-invalid @enderror"
                                    data-placeholder="--{{ __('Choose :field', ['field' => __('Type') ]) }}--"
                                    data-allow-clear="true"
                                    required>
                                    @foreach (\App\Enum\DenominationType::toArray() as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>

                                <x-invalid-feedback :name="'type'"/>
                            </div>
                            {{-- /.type --}}

                            {{-- quantity_per_bundle --}}
                            <div class="form-group col-12 col-lg-6">
                                <label for="quantity_per_bundle">{{ __('Quantity Per Bundle') }}</label>

                                <input type="number"
                                    name="quantity_per_bundle"
                                    id="quantity_per_bundle"
                                    class="form-control @error('quantity_per_bundle') is-invalid @enderror"
                                    value="{{ old('quantity_per_bundle', $denomination->quantity_per_bundle) }}"
                                    min="0"
                                    required
                                    autofocus>

                                <x-invalid-feedback :name="'quantity_per_bundle'"/>
                            </div>
                            {{-- /.quantity_per_bundle --}}

                            {{-- minimum_order_bundle --}}
                            <div class="form-group col-12 col-lg-3">
                                <label for="minimum_order_bundle">{{ __('Minimum Order Bundle') }}</label>

                                <input type="number"
                                    name="minimum_order_bundle"
                                    id="minimum_order_bundle"
                                    class="form-control @error('minimum_order_bundle') is-invalid @enderror"
                                    value="{{ old('minimum_order_bundle', $denomination->minimum_order_bundle) }}"
                                    min="0"
                                    required
                                    autofocus>

                                <x-invalid-feedback :name="'minimum_order_bundle'"/>
                            </div>
                            {{-- /.minimum_order_bundle --}}

                            {{-- maximum_order_bundle --}}
                            <div class="form-group col-12 col-lg-3">
                                <label for="maximum_order_bundle">{{ __('Maximum Order Bundle') }}</label>

                                <input type="number"
                                    name="maximum_order_bundle"
                                    id="maximum_order_bundle"
                                    class="form-control @error('maximum_order_bundle') is-invalid @enderror"
                                    value="{{ old('maximum_order_bundle', $denomination->maximum_order_bundle) }}"
                                    min="0"
                                    required
                                    autofocus>

                                <x-invalid-feedback :name="'maximum_order_bundle'"/>
                            </div>
                            {{-- /.maximum_order_bundle --}}

                            {{-- image --}}
                            <div class="form-group col-12 col-lg-6">
                                <label for="image">{{ __('Image') }}</label>

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
                                @if ($denomination->getRawOriginal('image'))
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

                        @include('components.form-timestamps', ['model' => $denomination])
                    </div>

                    <div class="card-footer">
                        <a href="{{ route('admin.denomination.index') }}" class="btn btn-secondary">
                            <i class="fa fa-chevron-left"></i> <span>{{ __('Go back') }}</span>
                        </a>

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
</x-app-layout>
