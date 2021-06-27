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
            'name' => 'BCA KCU Denpasar',
            'address' => 'Jl. Hasanuddin No.58, Pemecutan, Kec. Denpasar Bar., Kota Denpasar, Bali 80232',
            'address_latitude' => '-8.6581162',
            'address_longitude' => '115.2127812',
            'google_map_url' => 'https://www.google.com/maps/place/BCA+KCU+Denpasar/@-8.6581162,115.2127812,20z/data=!4m5!3m4!1s0x2dd240987c02083b:0x45b03e6b3ab46412!8m2!3d-8.6583514!4d115.2127877',
        ];
    }
}
