<strong>❓ Mohon konfirmasi, apakah data anda di bawah ini sudah benar?</strong>

Username: {{ $customer->username }}
{{ __('Full name') }}: {{ $customer->fullname }}
{{ __('Gender') }}: {{ $customer->gender->label }}
{{ __('Email Address') }}: {{ $customer->email }}
{{ __('Phone Number') }}: {{ $customer->phone }}
{{ __('Whatsapp Phone Number') }}: <a href="{{ $customer->whatsapp_phone_url }}">{{ $customer->whatsapp_phone }}</a>
{{ __('Account Number') }}: {{ $customer->account_number ?? '-' }}
{{ __('Identity Card Number') }}: {{ $customer->identitycard_number ?? '-' }}
{{ __('Google Map Address') }}: {{ $customer->google_map_url }}
