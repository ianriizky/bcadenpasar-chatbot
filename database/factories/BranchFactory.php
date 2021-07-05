<?php

namespace Database\Factories;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;

class BranchFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Branch::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => 'BCA ' . $this->faker->company,
            'address' => $this->faker->address,
            'address_latitude' => $latitude = $this->faker->latitude,
            'address_longitude' => $longitude = $this->faker->longitude,
            'google_map_url' => $this->faker->randomElement([
                null,
                google_map_url($latitude, $longitude)
            ]),
        ];
    }
}
