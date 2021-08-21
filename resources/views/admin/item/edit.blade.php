@include('admin.item.form', [
    'item' => $item,
    'url' => route('admin.order.item.edit', [
        'order' => $item->order,
        'item' => $item->denomination_value,
    ]),
    'icon' => 'fa-edit',
    'title' => __('Edit :name', ['name' => __(':resource Details', ['resource' => __('admin-lang.order')])]),
    'action' => route('admin.order.item.update', [
        'order' => $item->order,
        'item' => $item->denomination_value,
    ]),
    'method' => 'PUT',
])
