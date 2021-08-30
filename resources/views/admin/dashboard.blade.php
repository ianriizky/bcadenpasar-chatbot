<x-admin-layout>
    <section class="section">
        <div class="section-header">
            <h1>{{ __('Dashboard') }}</h1>

            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <span>{{ __('Home') }}</span>
                </div>

                <div class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-fire"></i> <span>{{ __('Dashboard') }}</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="section-body">
        </div>
    </section>
</x-admin-layout>
