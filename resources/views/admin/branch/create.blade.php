@include('admin.branch.form', [
    'branch' => new \App\Models\Role,
    'url' => route('admin.branch.create'),
    'icon' => 'fa-plus-square',
    'title' => __('Create :name', ['name' => __('admin-lang.branch')]),
    'submit_action' => route('admin.branch.store'),
])
