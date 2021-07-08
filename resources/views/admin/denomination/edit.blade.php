@include('admin.denomination.form', [
    'denomination' => $denomination,
    'url' => route('admin.denomination.edit', $denomination),
    'icon' => 'fa-edit',
    'title' => __('Edit :name', ['name' => __('admin-lang.denomination')]),
    'action' => route('admin.denomination.update', $denomination),
    'method' => 'PUT',
])
