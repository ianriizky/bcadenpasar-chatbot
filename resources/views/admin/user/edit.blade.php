@include('admin.user.form', [
    'user' => $user,
    'url' => route('admin.user.edit', $user),
    'icon' => 'fa-edit',
    'title' => __('Edit :name', ['name' => __('admin-lang.user')]),
    'action' => route('admin.user.update', $user),
    'method' => 'PUT',
])
