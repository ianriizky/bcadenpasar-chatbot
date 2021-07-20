<strong>⚠️ Berikut ini adalah data detail penukaran uang anda. </strong>

{{ __('Order Code') }}: {{ $order->code }}
{{ __('admin-lang.customer') }}: {{ $order->customer->fullname }}
{{ __('admin-lang.user') }}: @if ($order->user) {{ $order->user->fullname }} @else {{ __('Unscheduled') }} @endif
{{ __('admin-lang.branch') }}:
@unless ($order->branch)
    <em>{{ __('Unscheduled') }}</em>
@else
    ‣ {{ __('Name') }}: {{ $order->branch->name }}
    ‣ {{ __('Address') }}: {{ $order->branch->address }}
    ‣ {{ __('Google Map Address') }}: {{ $order->branch->google_map_url }}
@endunless
{{ __(':resource Details', ['resource' => __('admin-lang.order')]) }}:
@foreach ($order->items as $index => $item)
    ‣ ({{ $index + 1 }}) {{ $item->denomination->value_rupiah }} ({{ $item->denomination_name }})
        ‣ {{ __('Bundle Quantity') }}: {{ $item->bundle_quantity }} {{ __('bundle') }}
        ‣ {{ __('Quantity') }}: {{ $item->quantity }} {{ Str::lower($item->denomination->type->label) }}
        ‣ {{ __('Total:') }} {{ format_rupiah($item->total) }}
@endforeach

{{ __(':amount Total', ['amount' => __('Bundle Quantity')]) }}: {{ $order->item_total_bundle_quantity }} {{ __('bundle') }}
{{ __(':amount Total', ['amount' => __('admin-lang.order')]) }}: {{ format_rupiah($order->item_total) }}
