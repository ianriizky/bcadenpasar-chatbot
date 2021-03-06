@include('admin.customer.form', [
    'customer' => new ModelsCustomer,
    'url' => route('admin.customer.create'),
    'icon' => 'fa-plus-square',
    'title' => __('Create :name', ['name' => __('admin-lang.customer')]),
    'action' => route('admin.customer.store'),
])
