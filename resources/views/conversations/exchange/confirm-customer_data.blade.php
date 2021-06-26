<strong>❓ Mohon konfirmasi, apakah data anda di bawah ini sudah benar?</strong>

Username: {{ $customer->username }}
{{ __('Full name') }}: {{ $customer->fullname }}
{{ __('Gender') }}: {{ $customer->gender->label }}
{{ __('Email Address') }}: {{ $customer->email }}
{{ __('Phone Number') }}: {{ $customer->phone }}
{{ __('Whatsapp Phone Number') }}: {{ $customer->whatsapp_phone }}
{{ __('Account Number') }}: {{ $customer->account_number ?? '-' }}
{{ __('Identity Card Number') }}: {{ $customer->identitycard_number ?? '-' }}
{{ __('Identity Card Image') }}: {{ $customer->identitycard_image ?? '-' }}
Google Map URL: {{ $customer->google_map_url }}
