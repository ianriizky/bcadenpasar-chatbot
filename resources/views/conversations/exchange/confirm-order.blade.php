<strong>⚠️ Berikut ini adalah data detail penukaran uang anda. </strong>

{{ __('admin-lang.customer') }}: {{ $order->customer->fullname }}
{{ __(':resource Details', ['resource' => __('admin-lang.order')]) }}:
@foreach ($order->items as $index => $item)
    {{ $index + 1 }}. {{ $item->denomination->value_rupiah }} ({{ $item->denomination_name }})
        {{ __('Bundle Quantity') }}: {{ $item->bundle_quantity }} {{ __('bundle') }}
        {{ __('Quantity') }}: {{ $item->quantity }} {{ Str::lower($item->denomination->type->label) }}
        {{ __('Total:') }} {{ format_rupiah($item->total) }}
@endforeach

{{ __(':amount Total', ['amount' => __('Bundle Quantity')]) }}: {{ $order->item_total_bundle_quantity }} {{ __('bundle') }}
{{ __(':amount Total', ['amount' => __('admin-lang.order')]) }}: {{ format_rupiah($order->item_total) }}
