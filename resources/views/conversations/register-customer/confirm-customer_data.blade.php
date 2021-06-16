<strong>❓ Mohon konfirmasi, apakah data anda di bawah ini sudah benar?</strong>

chat_id: {{ $user->getId() }}
username: {{ $user->getUsername() }}
fullname: {{ $userStorage->get('fullname') }}
gender: {{ $userStorage->get('gender') }}
email: {{ $userStorage->get('email') }}
phone_country: {{ env('PHONE_COUNTRY') }}
phone: {{ $userStorage->get('phone') }}
whatsapp_phone_country: {{ env('PHONE_COUNTRY') }}
whatsapp_phone: {{ $userStorage->get('whatsapp_phone', '-') }}
account_number: {{ $userStorage->get('account_number', '-') }}
identitycard_number: {{ $userStorage->get('identitycard_number', '-') }}
identitycard_image: {{ $userStorage->get('identitycard_image', '-') }}
location_latitude: {{ $userStorage->get('location_latitude', '-') }}
location_longitude: {{ $userStorage->get('location_longitude', '-') }}
