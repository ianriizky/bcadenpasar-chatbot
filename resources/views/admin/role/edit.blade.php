@include('admin.role.form', [
    'role' => $role,
    'url' => route('admin.role.edit', $role),
    'icon' => 'fa-edit',
    'title' => __('Edit :name', ['name' => __('admin-lang.role')]),
    'action' => route('admin.role.update', $role),
    'method' => 'PUT',
])
