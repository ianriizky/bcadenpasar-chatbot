<strong>Mohon konfirmasi data anda di bawah ini.</strong>

username: {{ $user->getUsername() }}
fullname: {{ $response['fullname'] }}
gender: {{ $userStorage->get('gender') }}
email: {{ $response['email'] }}
phone_country: {{ env('PHONE_COUNTRY') }}
phone: {{ $response['phone'] }}
whatsapp_phone_country: {{ env('PHONE_COUNTRY') }}
whatsapp_phone: {{ $response['whatsapp_phone'] ?? '-' }}
accountnumber: {{ $response['accountnumber'] ?? '-' }}
identitycardnumber: {{ $response['identitycardnumber'] ?? '-' }}
identitycardimage: {{ $response['identitycardimage'] ?? '-' }}
location_latitude: {{ $response['location_latitude'] ?? '-' }}
location_longitude: {{ $response['location_longitude'] ?? '-' }}
