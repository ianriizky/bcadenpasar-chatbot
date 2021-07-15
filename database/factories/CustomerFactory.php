<?php

namespace Database\Factories;

use App\Enum\Gender;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class CustomerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Customer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $gender = $this->faker->randomElement(Gender::toValues());

        return [
            'telegram_chat_id' => $this->faker->numerify('#########'),
            'username' => $this->faker->userName,
            'fullname' => $this->faker->name($gender),
            'gender' => $gender,
            'email' => $this->faker->unique()->safeEmail(),
            'phone_country' => env('PHONE_COUNTRY', 'ID'),
            'phone' => $this->faker->numerify('081#########'),
            'whatsapp_phone_country' => env('PHONE_COUNTRY', 'ID'),
            'whatsapp_phone' => $this->faker->numerify('081#########'),
            'account_number' => $this->faker->numerify('#########'),
            'identitycard_number' => $this->faker->numerify('################'),
            'identitycard_image' => UploadedFile::fake()->image(Str::random() . '.jpg'),
            'location_latitude' => $this->faker->latitude,
            'location_longitude' => $this->faker->longitude,
        ];
    }
}
