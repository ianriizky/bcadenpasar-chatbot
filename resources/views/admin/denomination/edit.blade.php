@include('admin.denomination.form', [
    'denomination' => $denomination,
    'title' => __('Edit :name', ['name' => __('admin-lang.denomination')]),
    'icon' => 'fa-edit',
    'action' => route('admin.denomination.update', $denomination),
    'method' => 'PUT',
])
