@include('admin.user.form', [
    'user' => new \App\Models\User,
    'title' => __('Create :name', ['name' => __('admin-lang.user')]),
    'icon' => 'fa-plus-square',
    'action' => route('admin.user.store'),
])
