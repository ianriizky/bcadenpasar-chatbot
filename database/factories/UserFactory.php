<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $gender = $this->faker->randomElement(['male', 'female']);

        return [
            'username' => $this->faker->userName,
            'fullname' => $this->faker->name($gender),
            'gender' => $gender,
            'email' => $this->faker->unique()->safeEmail(),
            'phone_country' => env('PHONE_COUNTRY', 'ID'),
            'phone' => $this->faker->numerify('081#########'),
            'email_verified_at' => Carbon::now(),
            'password' => 'password',
            'remember_token' => Str::random(10),
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    /**
     * Indicate that the model's account should be unactivate.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unactivate()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => false,
            ];
        });
    }
}
