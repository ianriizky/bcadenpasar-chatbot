<?php

namespace Database\Seeders;

use App\Enum\DenominationType;
use App\Models\Denomination;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DenominationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        File::ensureDirectoryExists(Storage::path(Denomination::IMAGE_PATH));

        array_map(function (array $attributes) {
            File::copy(
                resource_path('img/denomination/' . $attributes['image']),
                Storage::path(Denomination::IMAGE_PATH . '/' . $attributes['image'])
            );

            return Denomination::create($attributes);
        }, array_merge($this->coins(), $this->banknotes()));
    }

    /**
     * Return list of coin seeder data.
     *
     * @return array
     */
    protected function coins(): array
    {
        return [
            [
                'code' => 'coin-100',
                'name' => 'Seratus',
                'value' => 100,
                'type' => DenominationType::coin(),
                'quantity_per_bundle' => 25,
                'minimum_order_bundle' => 1,
                'maximum_order_bundle' => 2,
                'minimum_order_quantity' => 1,
                'image' => 'coin-100.png',
            ],
            [
                'code' => 'coin-200',
                'name' => 'Dua ratus',
                'value' => 200,
                'type' => DenominationType::coin(),
                'quantity_per_bundle' => 25,
                'minimum_order_bundle' => 1,
                'maximum_order_bundle' => 2,
                'minimum_order_quantity' => 1,
                'image' => 'coin-200.png',
            ],
            [
                'code' => 'coin-500',
                'name' => 'Lima ratus',
                'value' => 500,
                'type' => DenominationType::coin(),
                'quantity_per_bundle' => 25,
                'minimum_order_bundle' => 1,
                'maximum_order_bundle' => 2,
                'minimum_order_quantity' => 1,
                'image' => 'coin-500.png',
            ],
            [
                'code' => 'coin-1000',
                'name' => 'Seribu',
                'value' => 1000,
                'type' => DenominationType::coin(),
                'quantity_per_bundle' => 25,
                'minimum_order_bundle' => 1,
                'maximum_order_bundle' => 2,
                'minimum_order_quantity' => 1,
                'image' => 'coin-1000.jpg',
            ],
        ];
    }

    /**
     * Return list of banknote seeder data.
     *
     * @return array
     */
    protected function banknotes(): array
    {
        return [
            [
                'code' => 'banknote-1000',
                'name' => 'Seribu',
                'value' => 1000,
                'type' => DenominationType::banknote(),
                'quantity_per_bundle' => 100,
                'minimum_order_bundle' => 1,
                'maximum_order_bundle' => 2,
                'minimum_order_quantity' => 1,
                'image' => 'banknote-1000.jpg',
            ],
            [
                'code' => 'banknote-2000',
                'name' => 'Dua ribu',
                'value' => 2000,
                'type' => DenominationType::banknote(),
                'quantity_per_bundle' => 100,
                'minimum_order_bundle' => 1,
                'maximum_order_bundle' => 2,
                'minimum_order_quantity' => 1,
                'image' => 'banknote-2000.jpg',
                'is_visible' => true,
            ],
            [
                'code' => 'banknote-5000',
                'name' => 'Lima ribu',
                'value' => 5000,
                'type' => DenominationType::banknote(),
                'quantity_per_bundle' => 100,
                'minimum_order_bundle' => 1,
                'maximum_order_bundle' => 2,
                'minimum_order_quantity' => 1,
                'image' => 'banknote-5000.jpg',
                'is_visible' => true,
            ],
            [
                'code' => 'banknote-10000',
                'name' => 'Sepuluh ribu',
                'value' => 10000,
                'type' => DenominationType::banknote(),
                'quantity_per_bundle' => 100,
                'minimum_order_bundle' => 1,
                'maximum_order_bundle' => 1,
                'minimum_order_quantity' => 1,
                'image' => 'banknote-10000.jpg',
                'is_visible' => true,
            ],
            [
                'code' => 'banknote-20000',
                'name' => 'Dua puluh ribu',
                'value' => 20000,
                'type' => DenominationType::banknote(),
                'quantity_per_bundle' => 100,
                'minimum_order_bundle' => 1,
                'maximum_order_bundle' => 1,
                'minimum_order_quantity' => 1,
                'image' => 'banknote-20000.jpg',
                'is_visible' => true,
            ],
            [
                'code' => 'banknote-50000',
                'name' => 'Lima puluh ribu',
                'value' => 50000,
                'type' => DenominationType::banknote(),
                'quantity_per_bundle' => 100,
                'minimum_order_bundle' => 1,
                'maximum_order_bundle' => 1,
                'minimum_order_quantity' => 1,
                'image' => 'banknote-50000.jpg',
            ],
            [
                'code' => 'banknote-75000',
                'name' => 'Tujuh puluh lima ribu',
                'value' => 75000,
                'type' => DenominationType::banknote(),
                'quantity_per_bundle' => 100,
                'minimum_order_bundle' => 1,
                'maximum_order_bundle' => 1,
                'minimum_order_quantity' => 1,
                'image' => 'banknote-75000.jpg',
            ],
            [
                'code' => 'banknote-100000',
                'name' => 'Seratus ribu',
                'value' => 100000,
                'type' => DenominationType::banknote(),
                'quantity_per_bundle' => 100,
                'minimum_order_bundle' => 1,
                'maximum_order_bundle' => 1,
                'minimum_order_quantity' => 1,
                'image' => 'banknote-100000.jpg',
            ],
        ];
    }
}
