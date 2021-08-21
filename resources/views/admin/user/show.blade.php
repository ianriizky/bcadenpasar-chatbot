<x-admin-layout>
    <section class="section">
        <div class="section-header">
            <h1>{{ __('Show :name', ['name' => __('admin-lang.user')]) }}</h1>

            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ route('admin.user.index') }}">
                        <i class="fas fa-id-badge"></i> <span>{{ __('admin-lang.user') }}</span>
                    </a>
                </div>

                <div class="breadcrumb-item">
                    <a href="{{ route('admin.user.show', $user) }}">
                        <i class="fas fa-eye"></i> <span>{{ __('Show :name', ['name' => __('admin-lang.user')]) }}</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        {{-- branch_id --}}
                        <div class="form-group col-12 col-lg-6">
                            <label for="branch_id">{{ __('admin-lang.branch') }}</label>

                            <p class="form-control-plaintext">{{ $user->branch->name }}</p>
                        </div>
                        {{-- /.branch_id --}}

                        <div class="col-12 col-lg-6"></div>

                        {{-- username --}}
                        <div class="form-group col-12 col-lg-6">
                            <label for="username">Username</label>

                            <p class="form-control-plaintext">{{ $user->username }}</p>
                        </div>
                        {{-- /.username --}}

                        {{-- fullname --}}
                        <div class="form-group col-12 col-lg-6">
                            <label for="fullname">{{ __('Full name') }}</label>

                            <p class="form-control-plaintext">{{ $user->fullname }}</p>
                        </div>
                        {{-- /.fullname --}}

                        {{-- gender --}}
                        <div class="form-group col-12 col-lg-6">
                            <label for="gender">{{ __('Gender') }}</label>

                            <p class="form-control-plaintext">{{ $user->gender->label }}</p>
                        </div>
                        {{-- /.gender --}}

                        <div class="col-12 col-lg-6"></div>

                        {{-- email --}}
                        <div class="form-group col-12 col-lg-6">
                            <label for="email">{{ __('Email Address') }}</label>

                            <p class="form-control-plaintext">{{ $user->email }}</p>
                        </div>
                        {{-- /.email --}}

                        {{-- phone --}}
                        <div class="form-group col-12 col-lg-6">
                            <label for="phone">{{ __('Phone Number') }}</label>

                            <p class="form-control-plaintext">{{ $user->phone }}</p>
                        </div>
                        {{-- /.phone --}}

                        {{-- role --}}
                        <div class="form-group col-12 col-lg-6">
                            <label for="role">{{ __('Role') }}</label>

                            <p class="form-control-plaintext">{{ $user->role }}</p>
                        </div>
                        {{-- /.role --}}

                        {{-- is_active --}}
                        <div class="form-group col-12 col-lg-6">
                            <label for="is_active" class="d-block">{{ __('Active') }}</label>

                            <p class="form-control-plaintext">{!! $user->is_active_badge !!}</p>
                        </div>
                        {{-- /.is_active --}}

                        {{-- email_verified_at --}}
                        <div class="form-group col-12 col-lg-6">
                            <label for="email_verified_at">{{ __('Verify Email Address') }}</label>

                            <p class="form-control-plaintext">
                                @if ($user->email_verified_at)
                                    {{ $user->email_verified_at->translatedFormat('d F Y H:i:s') }}
                                @else
                                    <span class="badge badge-warning">{{ __('Unverified') }}</span>
                                @endif
                            </p>
                        </div>
                        {{-- /.email_verified_at --}}
                    </div>

                    @include('components.form-timestamps', ['model' => $user])
                </div>

                <div class="card-footer">
                    @include('components.datatables.link-back', ['url' => route('admin.user.index')])

                    @can('update', $user)
                        @include('components.datatables.link-edit', ['url' => route('admin.user.edit', $user)])
                    @endcan
                </div>
            </div>
        </div>
    </section>
</x-admin-layout>
