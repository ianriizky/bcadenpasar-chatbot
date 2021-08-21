@section('pre-style')
    <link rel="stylesheet" href="{{ mix('node_modules/select2/dist/css/select2.min.css') }}">
@endsection

@section('script')
    <script src="{{ mix('node_modules/select2/dist/js/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2();

            @include('components.select2-change', ['olds' => Arr::except(old() ?: $user, '_token')])
        });
    </script>
@endsection

<x-admin-layout>
    <section class="section">
        <div class="section-header">
            <h1>{{ $title }}</h1>

            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ route('admin.user.index') }}">
                        <i class="fas fa-id-badge"></i> <span>{{ __('admin-lang.user') }}</span>
                    </a>
                </div>

                <div class="breadcrumb-item">
                    <a href="{{ $url }}">
                        <i class="fas {{ $icon }}"></i> <span>{{ $title }}</span>
                    </a>
                </div>
            </div>
        </div>

        <form method="post">
            @csrf

            <div class="section-body">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            {{-- branch_id --}}
                            <div class="form-group col-12 col-lg-6">
                                <label for="branch_id">{{ __('admin-lang.branch') }}<span class="text-danger">*</span></label>

                                <select name="branch_id"
                                    id="branch_id"
                                    class="form-control select2 @error('branch_id') is-invalid @enderror"
                                    data-placeholder="--{{ __('Choose :field', ['field' => __('admin-lang.branch') ]) }}--"
                                    data-allow-clear="true"
                                    required
                                    autofocus>
                                    @foreach (\App\Models\Branch::pluck('name', 'id') as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>

                                <x-invalid-feedback :name="'branch_id'"/>
                            </div>
                            {{-- /.branch_id --}}

                            <div class="col-12 col-lg-6"></div>

                            {{-- username --}}
                            <div class="form-group col-12 col-lg-6">
                                <label for="username">Username<span class="text-danger">*</span></label>

                                <input type="text"
                                    name="username"
                                    id="username"
                                    class="form-control @error('username') is-invalid @enderror"
                                    value="{{ old('username', $user->username) }}"
                                    required>

                                <x-invalid-feedback :name="'username'"/>
                            </div>
                            {{-- /.username --}}

                            {{-- fullname --}}
                            <div class="form-group col-12 col-lg-6">
                                <label for="fullname">{{ __('Full name') }}<span class="text-danger">*</span></label>

                                <input type="text"
                                    name="fullname"
                                    id="fullname"
                                    class="form-control @error('fullname') is-invalid @enderror"
                                    value="{{ old('fullname', $user->fullname) }}"
                                    required>

                                <x-invalid-feedback :name="'fullname'"/>
                            </div>
                            {{-- /.fullname --}}

                            {{-- gender --}}
                            <div class="form-group col-12 col-lg-6">
                                <label for="gender">{{ __('Gender') }}<span class="text-danger">*</span></label>

                                <select name="gender"
                                    id="gender"
                                    class="form-control select2 @error('gender') is-invalid @enderror"
                                    data-placeholder="--{{ __('Choose :field', ['field' => __('Gender') ]) }}--"
                                    data-allow-clear="true"
                                    required>
                                    @foreach (\App\Enum\Gender::toArray() as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>

                                <x-invalid-feedback :name="'gender'"/>
                            </div>
                            {{-- /.gender --}}

                            <div class="col-12 col-lg-6"></div>

                            {{-- email --}}
                            <div class="form-group col-12 col-lg-6">
                                <label for="email">{{ __('Email Address') }}<span class="text-danger">*</span></label>

                                <input type="email"
                                    name="email"
                                    id="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $user->email) }}"
                                    required>

                                <x-invalid-feedback :name="'email'"/>
                            </div>
                            {{-- /.email --}}

                            {{-- phone_country --}}
                            <input type="hidden" name="phone_country" value="{{ env('PHONE_COUNTRY', 'ID') }}">
                            {{-- /.phone_country --}}

                            {{-- phone --}}
                            <div class="form-group col-12 col-lg-6">
                                <label for="phone">{{ __('Phone Number') }}<span class="text-danger">*</span></label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">🇮🇩</div>
                                    </div>

                                    <input type="tel"
                                        name="phone"
                                        id="phone"
                                        class="form-control @error('phone') is-invalid @enderror"
                                        value="{{ old('phone', $user->phone) }}"
                                        required>

                                    <x-invalid-feedback :name="'phone'"/>
                                </div>
                            </div>
                            {{-- /.phone --}}

                            {{-- password --}}
                            <div class="form-group col-12 col-lg-6">
                                @unless ($user->exists)
                                    <label for="password">{{ __('Password') }}<span class="text-danger">*</span></label>
                                @else
                                    <label for="password">{{ __('Reset Password') }}</label>
                                @endunless

                                <input type="password"
                                    name="password"
                                    id="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    @unless ($user->exists) required @endunless>

                                <x-invalid-feedback :name="'password'"/>
                            </div>
                            {{-- /.password --}}

                            {{-- password_confirmation --}}
                            <div class="form-group col-12 col-lg-6">
                                <label for="password_confirmation">{{ __('Confirm Password') }}@unless ($user->exists) <span class="text-danger">*</span> @endunless</label>

                                <input type="password"
                                    name="password_confirmation"
                                    id="password_confirmation"
                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                    @unless ($user->exists) required @endunless>

                                <x-invalid-feedback :name="'password_confirmation'"/>
                            </div>
                            {{-- /.password_confirmation --}}

                            {{-- role --}}
                            <div class="form-group col-12 col-lg-6">
                                <label for="role">{{ __('Role') }}<span class="text-danger">*</span></label>

                                <select name="role"
                                    id="role"
                                    class="form-control select2 @error('role') is-invalid @enderror"
                                    data-placeholder="--{{ __('Choose :field', ['field' => __('Role') ]) }}--"
                                    data-allow-clear="true"
                                    required>
                                    @foreach (\App\Models\Role::getRoles() as $role)
                                        <option value="{{ $role }}">{{ $role }}</option>
                                    @endforeach
                                </select>

                                <x-invalid-feedback :name="'role'"/>
                            </div>
                            {{-- /.role --}}

                            {{-- is_active --}}
                            <div class="form-group col-12 col-lg-6">
                                <label for="is_active" class="d-block">{{ __('Active') }}<span class="text-danger">*</span></label>

                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio"
                                        name="is_active"
                                        id="is_active_true"
                                        value="1"
                                        @if (old('is_active', $user->is_active)) checked @endif
                                        class="custom-control-input">

                                    <label class="custom-control-label" for="is_active_true">{{ __('Yes') }}</label>
                                </div>

                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio"
                                        name="is_active"
                                        id="is_active_false"
                                        value="0"
                                        @unless (old('is_active', $user->is_active)) checked @endunless
                                        class="custom-control-input">

                                    <label class="custom-control-label" for="is_active_false">{{ __('No') }}</label>
                                </div>

                                <x-invalid-feedback :name="'is_active'"/>
                            </div>
                            {{-- /.is_active --}}
                        </div>

                        @if ($user->exists)
                            <div class="row">
                                {{-- email_verified_at --}}
                                <div class="form-group col-12 col-lg-6">
                                    <label for="email_verified_at">{{ __('Verify Email Address') }}</label>

                                    <p class="form-control-plaintext">
                                        @if ($user->email_verified_at)
                                            {{ $user->email_verified_at->translatedFormat('d F Y H:i:s') }}
                                        @else
                                            <span class="badge badge-warning">{{ __('Unverified') }}</span>

                                            @isset($verify_url)
                                                <a href="{{ $verify_url }}"
                                                    onclick="return (confirm('{{ __('Are you sure you want to run this action?') }}'))"
                                                    class="btn btn-info btn-round btn-sm">
                                                    <i class="fa fa-user-check"></i> <span>{{ __('Manually Verify Email Address') }}</span>
                                                </a>
                                            @endisset
                                        @endif
                                    </p>
                                </div>
                                {{-- /.email_verified_at --}}
                            </div>
                        @endif

                        @includeWhen($user->exists, 'components.form-timestamps', ['model' => $user])
                    </div>

                    <div class="card-footer">
                        @can('viewAny', \App\Models\User::class)
                            <a href="{{ route('admin.user.index') }}" class="btn btn-secondary">
                                @include('components.datatables.button-back')
                            </a>
                        @endcan

                        @isset($destroy_action)
                            <button type="submit"
                                formaction="{{ $destroy_action }}"
                                @isset($method) name="_method" value="DELETE" @endisset
                                onclick="return (confirm('{{ __('Are you sure you want to delete this data?') }}'))"
                                class="btn btn-danger">
                                <i class="fa fa-trash"></i> <span>{{ __('Delete') }}</span>
                            </button>
                        @endisset

                        <button type="submit"
                            formaction="{{ $submit_action }}"
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
