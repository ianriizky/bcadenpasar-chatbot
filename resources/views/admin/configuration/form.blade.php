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
                    <a href="{{ route('admin.configuration.index') }}">
                        <i class="fas fa-cog"></i> <span>{{ __('admin-lang.configuration') }}</span>
                    </a>
                </div>

                <div class="breadcrumb-item">
                    <a href="{{ route('admin.configuration.create') }}">
                        <i class="fas {{ $icon }}"></i> <span>{{ $title }}</span>
                    </a>
                </div>
            </div>
        </div>

        <form action="{{ $action }}" method="post">
            @csrf
            @isset($method) @method($method) @endisset

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                {{-- key --}}
                                <div class="form-group">
                                    <label for="key">{{ __('Key') }}</label>

                                    <input type="text"
                                        name="key"
                                        id="key"
                                        class="form-control col-12 col-lg-6 @error('key') is-invalid @enderror"
                                        value="{{ old('key', $configuration->key) }}"
                                        required
                                        autofocus>

                                    <x-invalid-feedback :name="'key'"/>
                                </div>
                                {{-- /.key --}}

                                {{-- value --}}
                                <div class="form-group">
                                    <label for="value">{{ __('Value') }}</label>

                                    <input type="text"
                                        name="value"
                                        id="value"
                                        class="form-control col-12 col-lg-6 @error('value') is-invalid @enderror"
                                        value="{{ old('value', $configuration->value) }}"
                                        required>

                                    <x-invalid-feedback :name="'value'"/>
                                </div>
                                {{-- /.key --}}

                                {{-- description --}}
                                <div class="form-group">
                                    <label for="description">{{ __('Description') }}</label>

                                    <textarea name="description"
                                        id="description"
                                        class="form-control col-12 col-lg-6 @error('description') is-invalid @enderror"
                                        style="resize: vertical;">{{ old('description', $configuration->description) }}</textarea>

                                    <x-invalid-feedback :name="'description'"/>
                                </div>
                                {{-- /.description --}}
                            </div>

                            <div class="card-footer">
                                <a href="{{ route('admin.configuration.index') }}" class="btn btn-secondary">
                                    <i class="fa fa-chevron-left"></i> <span>{{ __('Go back') }}</span>
                                </a>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> <span>{{ __('Save') }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
</x-app-layout>
