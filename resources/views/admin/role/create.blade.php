@include('admin.role.form', [
    'role' => new \App\Models\Role,
    'url' => route('admin.role.create'),
    'icon' => 'fa-plus-square',
    'title' => __('Create :name', ['name' => __('admin-lang.role')]),
    'action' => route('admin.role.store'),
])
