@include('admin.denomination.form', [
    'denomination' => new \App\Models\Denomination,
    'url' => route('admin.denomination.create'),
    'icon' => 'fa-plus-square',
    'title' => __('Create :name', ['name' => __('admin-lang.denomination')]),
    'action' => route('admin.denomination.store'),
])
