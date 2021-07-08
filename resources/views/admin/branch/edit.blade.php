@include('admin.branch.form', [
    'branch' => $branch,
    'url' => route('admin.branch.edit', $branch),
    'icon' => 'fa-edit',
    'title' => __('Edit :name', ['name' => __('admin-lang.branch')]),
    'action' => route('admin.branch.update', $branch),
    'method' => 'PUT',
])
