<?php

namespace Database\Factories;

use App\Enum\DenominationType;
use App\Models\Denomination;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;

class DenominationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Denomination::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $isCoin = $this->faker->boolean;

        $denomination = $isCoin
            ? $this->faker->randomElement([100, 200, 500, 1000])
            : $this->faker->randomElement([1000, 2000, 5000, 10000, 20000, 50000, 75000, 100000]);

        return [
            'name' => terbilang($denomination),
            'value' => $denomination,
            'type' => $isCoin ? DenominationType::coin() : DenominationType::banknote(),
            'quantity_per_bundle' => $this->faker->randomElement([50, 100, 200]),
            'minimum_order_bundle' => $minimum = $this->faker->numberBetween(1, 10),
            'maximum_order_bundle' => $this->faker->numberBetween($minimum, 10),
            'image' => UploadedFile::fake()->image($denomination . '.jpg'),
        ];
    }
}
