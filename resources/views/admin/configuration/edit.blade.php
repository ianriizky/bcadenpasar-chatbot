@include('admin.configuration.form', [
    'configuration' => $configuration,
    'url' => route('admin.configuration.edit', $configuration),
    'icon' => 'fa-edit',
    'title' => __('Edit :name', ['name' => __('admin-lang.configuration')]),
    'action' => route('admin.configuration.update', $configuration),
    'method' => 'PUT',
])
