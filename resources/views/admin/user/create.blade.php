@include('admin.user.form', [
    'user' => new \App\Models\User,
    'url' => route('admin.user.create'),
    'icon' => 'fa-plus-square',
    'title' => __('Create :name', ['name' => __('admin-lang.user')]),
    'action' => route('admin.user.store'),
])
