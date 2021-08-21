@include('admin.order.form', [
    'order' => $order,
    'url' => route('admin.order.show', $order),
    'icon' => 'fa-edit',
    'title' => __('Edit :name', ['name' => __('admin-lang.order')]),
    'destroy_action' => route('admin.order.destroy', $order),
    'method' => 'PUT',
])
