<strong>❓ Mohon konfirmasi, apakah data anda di bawah ini sudah benar?</strong>

Username: {{ $user->getUsername() }}
{{ __('Full name') }}: {{ $userStorage->get('fullname') }}
{{ __('Gender') }}: {{ EnumGender::make($userStorage->get('gender'))->label }}
{{ __('Email Address') }}: {{ $userStorage->get('email') }}
{{ __('Phone Number') }}: {{ $userStorage->get('phone') }}
{{ __('Whatsapp Phone Number') }}: {{ $userStorage->get('whatsapp_phone') }}
{{ __('Account Number') }}: {{ $userStorage->get('account_number') ?? '-' }}
{{ __('Identity Card Number') }}: {{ $userStorage->get('identitycard_number') ?? '-' }}
