<x-app-layout>
    <section class="section">
        <div class="section-header">
            <h1>{{ __('Create :name', ['name' => __('admin-lang.user')]) }}</h1>

            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active">
                    <a href="{{ route('dashboard') }}">
                        <i class="fas fa-fire"></i> <span>{{ __('Dashboard') }}</span>
                    </a>
                </div>

                <div class="breadcrumb-item">
                    <a href="{{ route('user.index') }}">
                        <i class="fas fa-id-badge"></i> <span>{{ __('admin-lang.user') }}</span>
                    </a>
                </div>

                <div class="breadcrumb-item">
                    <i class="fas fa-plus-square"></i> <span>{{ __('Create :name', ['name' => __('admin-lang.user')]) }}</span>
                </div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">

                        </div>

                        <div class="card-body">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
