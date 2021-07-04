@include('admin.denomination.form', [
    'denomination' => new \App\Models\Denomination,
    'title' => __('Create :name', ['name' => __('admin-lang.denomination')]),
    'icon' => 'fa-money-bill-wave',
    'action' => route('admin.denomination.store'),
])
