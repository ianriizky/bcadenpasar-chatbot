@include('admin.configuration.form', [
    'configuration' => $configuration,
    'title' => __('Edit :name', ['name' => __('admin-lang.configuration')]),
    'icon' => 'fa-edit',
    'action' => route('admin.configuration.update', $configuration),
    'method' => 'PATCH',
])
