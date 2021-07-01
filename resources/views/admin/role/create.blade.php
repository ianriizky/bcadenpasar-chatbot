@include('admin.role.form', [
    'role' => new \App\Models\Configuration,
    'title' => __('Create :name', ['name' => __('admin-lang.role')]),
    'icon' => 'fa-plus-square',
    'action' => route('admin.role.store'),
])
