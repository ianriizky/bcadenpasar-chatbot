<strong>⚠️ Berikut ini adalah data detail penukaran uang anda. </strong>

{{ __('Order Code') }}: {{ $order->code }}
{{ __('admin-lang.customer') }}: {{ $order->customer->fullname }}
{{ __(':resource Details', ['resource' => __('admin-lang.order')]) }}:
@foreach ($order->items as $item)
    ‣ ({{ $loop->iteration }}) {{ $item->denomination->value_rupiah }} ({{ $item->denomination_name }})
        ‣ {{ __('Bundle Quantity') }}: {{ $item->bundle_quantity }} {{ __('bundle') }}
        ‣ {{ __('Quantity') }}: {{ $item->quantity }} {{ Str::lower($item->denomination->type->label) }}
        ‣ {{ __('Total') }}: {{ format_rupiah($item->total) }}
@endforeach

{{ __(':amount Total', ['amount' => __('Bundle Quantity')]) }}: {{ $order->item_total_bundle_quantity }} {{ __('bundle') }}
{{ __(':amount Total', ['amount' => __('admin-lang.order')]) }}: {{ format_rupiah($order->item_total) }}

<strong>{{ __('Order Status') }}: {{ $order->status->label }}</strong>
@if ($order->status->isCanceled() && $order->latestStatus->note)
    {{ __('Description') }}: {{ $order->latestStatus->note }}
@endif
@if ($order->status->isScheduled() || $order->status->isRescheduled())
{{ __('admin-lang.user') }}: {{ $order->user ? $order->user->fullname : __('Unscheduled') }}
{{ __('admin-lang.branch') }}:
    ‣ {{ __('Name') }}: {{ $order->branch->name }}
    ‣ {{ __('Address') }}: {{ $order->branch->address }}
    ‣ {{ __('Google Map Address') }}: {{ $order->branch->google_map_url }}
@endif
