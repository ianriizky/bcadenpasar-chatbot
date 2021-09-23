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
        $canOrderCustomQuantity = $this->faker->boolean;

        $type = $isCoin ? DenominationType::coin() : DenominationType::banknote();

        $value = $isCoin
            ? $this->faker->randomElement([100, 200, 500, 1000])
            : $this->faker->randomElement([1000, 2000, 5000, 10000, 20000, 50000, 75000, 100000]);

        return [
            'code' => $type->value . '-' . $value,
            'name' => terbilang($value),
            'value' => $value,
            'type' => $type,
            'quantity_per_bundle' => $this->faker->randomElement([50, 100, 200]),
            'minimum_order_bundle' => $minimumBundle = $this->faker->numberBetween(1, 10),
            'maximum_order_bundle' => $this->faker->numberBetween($minimumBundle, 10),
            'minimum_order_quantity' => $canOrderCustomQuantity ? $minimumQuantity = $this->faker->numberBetween(1, 100) : null,
            'maximum_order_quantity' => $canOrderCustomQuantity ? $this->faker->numberBetween($minimumQuantity, 100) : null,
            'can_order_custom_quantity' => $canOrderCustomQuantity,
            'is_visible' => $this->faker->boolean,
            'image' => UploadedFile::fake()->image($type->value . '-' . $value . '.jpg'),
        ];
    }
}
