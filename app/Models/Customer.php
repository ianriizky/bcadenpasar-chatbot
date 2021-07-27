<?php

namespace App\Models;

use App\Enum\Gender;
use App\Infrastructure\Database\Eloquent\Model;
use App\Models\Contracts\HasTelegramChatId;
use App\Models\Contracts\Issuerable;
use App\Models\Support\HasIssuerable;
use BotMan\BotMan\Interfaces\UserInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Collection;
use Propaganistas\LaravelPhone\Casts\E164PhoneNumberCast;

class Customer extends Model implements Issuerable, HasTelegramChatId
{
    use HasFactory, HasIssuerable,
        Concerns\Customer\Attribute,
        Concerns\Customer\Event,
        Concerns\Customer\Relation;

    /**
     * Path value for identitycard_image storage.
     *
     * @var string
     */
    const IDENTITYCARD_IMAGE_PATH = 'identity_card';

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'telegram_chat_id',
        'username',
        'fullname',
        'gender',
        'email',
        'phone_country',
        'phone',
        'whatsapp_phone_country',
        'whatsapp_phone',
        'account_number',
        'identitycard_number',
        'identitycard_image',
        'location_latitude',
        'location_longitude',
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'gender' => Gender::class,
        'phone' => E164PhoneNumberCast::class,
        'whatsapp_phone' => E164PhoneNumberCast::class,
        'location_latitude' => 'float',
        'location_longitude' => 'float',
    ];

    /**
     * {@inheritDoc}
     */
    public function getTelegramChatId(): string
    {
        return $this->telegram_chat_id;
    }

    /**
     * Retrieve model data by the given BotMan user data.
     *
     * @param  \BotMan\BotMan\Interfaces\UserInterface  $user
     * @return static
     */
    public static function retrieveByBotManUser(UserInterface $user)
    {
        return static::firstWhere([
            ['telegram_chat_id', $user->getId()],
            ['username', $user->getUsername()],
        ]);
    }

    /**
     * Retrieve model data by the given credentials ("username", "email").
     *
     * @param  array  $credentials
     * @return static
     */
    public static function retrieveByUsernameAndEmail(array $credentials)
    {
        return static::firstWhere([
            ['username', $credentials['username']],
            ['email', $credentials['email']],
        ]);
    }

    /**
     * Create model data and store to the storage based on the given BotMan user data.
     *
     * @param  \BotMan\BotMan\Interfaces\UserInterface  $user
     * @param  \Illuminate\Support\Collection  $storage
     * @return static
     */
    public static function updateOrCreateByBotManUser(UserInterface $user, Collection $storage)
    {
        return static::updateOrCreate([
            'telegram_chat_id' => $user->getId(),
            'username' => $user->getUsername(),
        ], [
            'telegram_chat_id' => $user->getId(),
            'username' => $user->getUsername(),
            'fullname' => $storage->get('fullname'),
            'gender' => $storage->get('gender'),
            'email' => $storage->get('email'),
            'phone_country' => $storage->get('phone_country', env('PHONE_COUNTRY')),
            'phone' => $storage->get('phone'),
            'whatsapp_phone_country' => $storage->get('whatsapp_phone_country', env('PHONE_COUNTRY')),
            'whatsapp_phone' => $storage->get('whatsapp_phone'),
            'account_number' => $storage->get('account_number'),
            'identitycard_number' => $storage->get('identitycard_number'),
            'identitycard_image' => $storage->get('identitycard_image'),
            'location_latitude' => $storage->get('location_latitude'),
            'location_longitude' => $storage->get('location_longitude'),
        ]);
    }
}
