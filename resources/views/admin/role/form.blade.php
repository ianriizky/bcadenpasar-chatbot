@section('pre-style')
    <link rel="stylesheet" href="{{ asset('node_modules/select2/dist/css/select2.min.css') }}">
@endsection

@section('script')
    <script src="{{ asset('node_modules/select2/dist/js/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2();

            const olds = @json(Arr::except(old() ?: $role, '_token'));

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
                    <a href="{{ route('admin.role.index') }}">
                        <i class="fas fa-user-tag"></i> <span>{{ __('admin-lang.role') }}</span>
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
                            {{-- name --}}
                            <div class="form-group col-12 col-lg-6">
                                <label for="name">{{ __('Name') }}<span class="text-danger">*</span></label>

                                <input type="text"
                                    name="name"
                                    id="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $role->name) }}"
                                    required
                                    autofocus>

                                <x-invalid-feedback :name="'name'"/>
                            </div>
                            {{-- /.name --}}

                            <div class="col-12 col-lg-6"></div>

                            {{-- guard_name --}}
                            <div class="form-group col-12 col-lg-6">
                                <label for="guard_name">{{ __('Guard Name') }}<span class="text-danger">*</span></label>

                                <select name="guard_name"
                                    id="guard_name"
                                    class="form-control select2 @error('guard_name') is-invalid @enderror"
                                    data-placeholder="--{{ __('Choose :field', ['field' => __('Guard Name') ]) }}--"
                                    data-allow-clear="true"
                                    required>
                                    @foreach (array_keys(config('auth.guards')) as $name)
                                        <option value="{{ $name }}">{{ $name }}</option>
                                    @endforeach
                                </select>

                                <x-invalid-feedback :name="'guard_name'"/>
                            </div>
                            {{-- /.guard_name --}}
                        </div>

                        @includeWhen($role->exists, 'components.form-timestamps', ['model' => $role])
                    </div>

                    <div class="card-footer">
                        <a href="{{ route('admin.role.index') }}" class="btn btn-secondary">
                            <i class="fa fa-chevron-left"></i> <span>{{ __('Go back') }}</span>
                        </a>

                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> <span>{{ __('Save') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </section>
</x-app-layout>
