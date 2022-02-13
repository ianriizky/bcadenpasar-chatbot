<strong>⚠️ Baru saja terjadi permintaan penukaran uang pada {{ $order->created_at->translatedFormat('d F Y H:i') }} dengan data sebagai berikut.</strong>

{{ __('admin-lang.customer') }}: <a href="{{ route('admin.customer.show', $order->customer) }}">{{ $order->customer->fullname }}</a>
{{ __(':resource Details', ['resource' => __('admin-lang.order')]) }}:
@foreach ($order->items as $item)
    ‣ ({{ $loop->iteration }}) {{ $item->denomination->value_rupiah }} ({{ $item->denomination_name }})
        ‣ {{ __('Bundle Quantity') }}: {{ $item->bundle_quantity }} {{ __('bundle') }}
        ‣ {{ __('Quantity') }}: {{ $item->quantity }} {{ Str::lower($item->denomination->type->label) }}
        ‣ {{ __('Total') }}: {{ format_rupiah($item->total) }}
@endforeach

{{ __(':amount Total', ['amount' => __('Bundle Quantity')]) }}: {{ $order->item_total_bundle_quantity }} {{ __('bundle') }}
{{ __(':amount Total', ['amount' => __('admin-lang.order')]) }}: {{ format_rupiah($order->item_total) }}

<strong>Selengkapnya bisa diakses di {{ route('admin.order.show', $order) }}.</strong>
