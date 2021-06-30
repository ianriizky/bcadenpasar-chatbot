<?php

namespace Database\Seeders;

use App\Models\Configuration;
use Illuminate\Database\Seeder;

class ConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        array_map(fn (array $attributes) => Configuration::create($attributes), [
            [
                'key' => 'maximum_total_order_value',
                'value' => 44000000,
                'description' => 'Maximum total order value in one transaction/order',
            ],
            [
                'key' => 'maximum_order_per_day',
                'value' => 20,
                'description' => 'Maximum total order value in one transaction/order',
            ],
        ]);
    }
}
