@include('admin.customer.form', [
    'customer' => $customer,
    'url' => route('admin.customer.edit', $customer),
    'icon' => 'fa-edit',
    'title' => __('Edit :name', ['name' => __('admin-lang.customer')]),
    'action' => route('admin.customer.update', $customer),
    'method' => 'PUT',
])
