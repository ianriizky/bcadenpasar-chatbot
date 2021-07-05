@include('admin.branch.form', [
    'branch' => new \App\Models\Role,
    'title' => __('Create :name', ['name' => __('admin-lang.branch')]),
    'icon' => 'fa-plus-square',
    'action' => route('admin.branch.store'),
])
