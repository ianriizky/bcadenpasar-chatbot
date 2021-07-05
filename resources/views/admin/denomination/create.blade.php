@include('admin.denomination.form', [
    'denomination' => new \App\Models\Denomination,
    'title' => __('Create :name', ['name' => __('admin-lang.denomination')]),
    'icon' => 'fa-plus-square',
    'action' => route('admin.denomination.store'),
])
