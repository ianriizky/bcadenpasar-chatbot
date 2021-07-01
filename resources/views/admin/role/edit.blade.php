@include('admin.role.form', [
    'role' => $role,
    'title' => __('Edit :name', ['name' => __('admin-lang.role')]),
    'icon' => 'fa-edit',
    'action' => route('admin.role.update', $role),
    'method' => 'PATCH',
])
