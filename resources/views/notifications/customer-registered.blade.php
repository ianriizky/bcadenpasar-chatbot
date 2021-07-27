<strong>⚠️ Baru saja terjadi pendaftaran pelanggan pada dengan data sebagai berikut.</strong>

Telegram Chat ID: {{ $customer->telegram_chat_id }}
Username: {{ $customer->username }}
{{ __('Full name') }}: {{ $customer->fullname }}
{{ __('Gender') }}: {{ $customer->gender->label }}
{{ __('Email Address') }}: {{ $customer->email }}
{{ __('Phone Number') }}: {{ $customer->phone }}
{{ __('Whatsapp Phone Number') }}: {{ $customer->whatsapp_phone }}
{{ __('Account Number') }}: {{ $customer->account_number ?? '-' }}
{{ __('Identity Card Number') }}: {{ $customer->identitycard_number ?? '-' }}
{{ __('Identity Card Image') }}: {{ $customer->identitycard_image ?? '-' }}
{{ __('Google Map Address') }}: {{ $customer->google_map_url }}

<strong>Selengkapnya bisa diakses di {{ route('admin.customer.edit', $customer) }}.</strong>
