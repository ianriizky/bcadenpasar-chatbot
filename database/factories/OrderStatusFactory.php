<?php

namespace Database\Factories;

use App\Enum\OrderStatus as EnumOrderStatus;
use App\Models\OrderStatus as ModelOrderStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderStatusFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ModelOrderStatus::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'status' => EnumOrderStatus::from($this->faker->randomElement(EnumOrderStatus::toValues())),
            'note' => $this->faker->randomElement([null, $this->faker->text]),
        ];
    }

    /**
     * Indicate that the status is "draft".
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function draft()
    {
        return $this->state([
            'status' => EnumOrderStatus::draft(),
        ]);
    }
}
