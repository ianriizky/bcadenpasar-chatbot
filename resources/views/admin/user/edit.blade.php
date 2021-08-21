@include('admin.user.form', [
    'user' => $user,
    'url' => route('admin.user.edit', $user),
    'icon' => 'fa-edit',
    'title' => __('Edit :name', ['name' => __('admin-lang.user')]),
    'verify_url' => route('admin.user.verify-email-address', $user),
    'submit_action' => route('admin.user.update', $user),
    'destroy_action' => route('admin.user.destroy', $user),
    'method' => 'PUT',
])
