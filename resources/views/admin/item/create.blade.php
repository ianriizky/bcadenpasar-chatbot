@include('admin.item.form', [
    'item' => $item,
    'url' => route('admin.order.item.create', $item->order),
    'icon' => 'fa-plus-square',
    'title' => __('Create :name', ['name' => __(':resource Details', ['resource' => __('admin-lang.order')])]),
    'action' => route('admin.order.item.store', $item->order),
])
