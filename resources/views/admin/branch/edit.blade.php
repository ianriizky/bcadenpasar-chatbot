@include('admin.branch.form', [
    'branch' => $branch,
    'url' => route('admin.branch.edit', $branch),
    'icon' => 'fa-edit',
    'title' => __('Edit :name', ['name' => __('admin-lang.branch')]),
    'submit_action' => route('admin.branch.update', $branch),
    'destroy_action' => route('admin.branch.destroy', $branch),
    'method' => 'PUT',
])
