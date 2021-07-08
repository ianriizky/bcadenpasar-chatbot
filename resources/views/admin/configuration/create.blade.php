@include('admin.configuration.form', [
    'configuration' => new \App\Models\Configuration,
    'url' => route('admin.configuration.create'),
    'icon' => 'fa-plus-square',
    'title' => __('Create :name', ['name' => __('admin-lang.configuration')]),
    'action' => route('admin.configuration.store'),
])
