<?php

namespace Database\Seeders;

use App\Enum\Gender;
use App\Models\Branch;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class BranchUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $branch_1 = Branch::create([
            'name' => 'BCA KCU Denpasar',
            'address' => 'Jl. Hasanuddin No.58, Pemecutan, Kec. Denpasar Bar., Kota Denpasar, Bali 80232',
            'address_latitude' => '-8.6581162',
            'address_longitude' => '115.2127812',
            'google_map_url' => 'https://goo.gl/maps/g2NwTkzhg5sXWkPD9',
        ]);

        /** @var \App\Models\User $admin */
        $admin_1 = User::make([
            'username' => env('ADMIN_USERNAME', 'admin'),
            'fullname' => env('ADMIN_FULLNAME', 'Administrator'),
            'gender' => env('ADMIN_GENDER', Gender::undefined()),
            'email' => env('ADMIN_EMAIL', 'admin@admin.com'),
            'phone_country' => env('PHONE_COUNTRY', 'ID'),
            'phone' => env('ADMIN_PHONE', '081000111000'),
            'email_verified_at' => Carbon::now(),
            'password' => env('ADMIN_PASSWORD', 'admin12345'),
            'is_active' => true,
        ])->setBranchRelationValue($branch_1);

        $admin_1->save();

        $admin_1->syncRoles(Role::ROLE_ADMIN);

        $branch_2 = Branch::create([
            'name' => 'BCA KCP Gianyar',
            'address' => 'Jl. By Pass Dharma Giri, Gianyar, Kec. Gianyar, Kabupaten Gianyar, Bali 80511',
            'address_latitude' => '-8.5408833',
            'address_longitude' => '115.3168043',
            'google_map_url' => 'https://goo.gl/maps/ud1KbDx3G1hKTd3v7',
        ]);
    }
}
