<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;
use Tests\Feature\Admin\DenominationTest;

class ItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Item::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $denomination = DenominationTest::createFromFactory();

        return [
            'denomination_id' => $denomination,
            'bundle_quantity' => $this->faker->randomElement($denomination->range_order_bundle),
        ];
    }
}
