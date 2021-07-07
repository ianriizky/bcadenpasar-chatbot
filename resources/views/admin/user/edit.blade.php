@include('admin.user.form', [
    'user' => $user,
    'title' => __('Edit :name', ['name' => __('admin-lang.user')]),
    'icon' => 'fa-edit',
    'action' => route('admin.user.update', $user),
    'method' => 'PUT',
])
