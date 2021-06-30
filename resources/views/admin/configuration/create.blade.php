@include('admin.configuration.form', [
    'configuration' => new \App\Models\Configuration,
    'title' => __('Create :name', ['name' => __('admin-lang.configuration')]),
    'icon' => 'fa-plus-square',
    'action' => route('admin.configuration.store'),
])
