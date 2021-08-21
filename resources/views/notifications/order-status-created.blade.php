<strong>⚠️ Status permintaan penukaran uang anda telah berubah menjadi {{ $order->status->label }} pada {{ $order->latestStatus->created_at->translatedFormat('d F Y H:i') }} dengan rincian sebagai berikut.</strong>

{{ __('admin-lang.customer') }}: <a href="{{ route('admin.customer.show', $order->customer) }}">{{ $order->customer->fullname }}</a>
{{ __('admin-lang.branch') }}: {{ $order->branch->name }} (Akses lokasi <a href="{{ $order->branch->google_map_url }}">di sini</a>)
{{ __('admin-lang.user') }}: {{ $order->user->fullname }}
{{ __('Schedule Date') }}: {{ $order->schedule_date->translatedFormat('d F Y H:i') }}
{{ __('Note') }}: {{ $order->latestStatus->note ?? 'tidak ada' }}
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
