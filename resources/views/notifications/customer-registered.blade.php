<strong>⚠️ Baru saja terjadi pendaftaran pelanggan pada {{ $customer->created_at->translatedFormat('d F Y H:i') }} dengan data sebagai berikut.</strong>

Telegram Chat ID: {{ $customer->telegram_chat_id }}
Username: {{ $customer->username }}
{{ __('Full name') }}: {{ $customer->fullname }}
{{ __('Gender') }}: {{ $customer->gender->label }}
{{ __('Email Address') }}: {{ $customer->email }}
{{ __('Phone Number') }}: {{ $customer->phone }}
{{ __('Whatsapp Phone Number') }}: <a href="{{ $customer->whatsapp_phone_url }}">{{ $customer->whatsapp_phone }}</a>
{{ __('Account Number') }}: {{ $customer->account_number ?? '-' }}
{{ __('Identity Card Number') }}: {{ $customer->identitycard_number ?? '-' }}
{{ __('Google Map Address') }}: {{ $customer->google_map_url }}

<strong>Selengkapnya bisa diakses di {{ route('admin.customer.show', $customer) }}.</strong>
