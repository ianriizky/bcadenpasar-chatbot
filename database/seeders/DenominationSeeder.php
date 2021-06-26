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
        }, [
            [
                'name' => 'Dua ribu',
                'value' => 2000,
                'type' => DenominationType::banknote(),
                'quantity_per_bundle' => 100,
                'minimum_order_bundle' => 1,
                'maximum_order_bundle' => 2,
                'image' => '2000.jpg',
            ],
            [
                'name' => 'Lima ribu',
                'value' => 5000,
                'type' => DenominationType::banknote(),
                'quantity_per_bundle' => 100,
                'minimum_order_bundle' => 1,
                'maximum_order_bundle' => 2,
                'image' => '5000.jpg',
            ],
            [
                'name' => 'Sepuluh ribu',
                'value' => 10000,
                'type' => DenominationType::banknote(),
                'quantity_per_bundle' => 100,
                'minimum_order_bundle' => 1,
                'maximum_order_bundle' => 1,
                'image' => '10000.jpg',
            ],
            [
                'name' => 'Dua puluh ribu',
                'value' => 20000,
                'type' => DenominationType::banknote(),
                'quantity_per_bundle' => 100,
                'minimum_order_bundle' => 1,
                'maximum_order_bundle' => 1,
                'image' => '20000.jpg',
            ],
        ]);
    }
}
