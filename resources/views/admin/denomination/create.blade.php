@include('admin.denomination.form', [
    'denomination' => new ModelsDenomination,
    'url' => route('admin.denomination.create'),
    'icon' => 'fa-plus-square',
    'title' => __('Create :name', ['name' => __('admin-lang.denomination')]),
    'action' => route('admin.denomination.store'),
])
