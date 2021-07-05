@include('admin.branch.form', [
    'branch' => $branch,
    'title' => __('Edit :name', ['name' => __('admin-lang.branch')]),
    'icon' => 'fa-edit',
    'action' => route('admin.branch.update', $branch),
    'method' => 'PUT',
])
