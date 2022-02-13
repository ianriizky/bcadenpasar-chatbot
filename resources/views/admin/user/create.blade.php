@include('admin.user.form', [
    'user' => new ModelsUser,
    'url' => route('admin.user.create'),
    'icon' => 'fa-plus-square',
    'title' => __('Create :name', ['name' => __('admin-lang.user')]),
    'submit_action' => route('admin.user.store'),
])
