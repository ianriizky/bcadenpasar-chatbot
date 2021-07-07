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
        $branch = Branch::create([
            'name' => 'BCA KCU Denpasar',
            'address' => 'Jl. Hasanuddin No.58, Pemecutan, Kec. Denpasar Bar., Kota Denpasar, Bali 80232',
            'address_latitude' => '-8.6581162',
            'address_longitude' => '115.2127812',
            'google_map_url' => 'https://www.google.com/maps/place/BCA+KCU+Denpasar/@-8.6581162,115.2127812,20z/data=!4m5!3m4!1s0x2dd240987c02083b:0x45b03e6b3ab46412!8m2!3d-8.6583514!4d115.2127877',
        ]);

        /** @var \App\Models\User $admin */
        $admin = User::make([
            'username' => env('ADMIN_USERNAME', 'admin'),
            'fullname' => env('ADMIN_FULLNAME', 'Administrator'),
            'gender' => env('ADMIN_GENDER', Gender::undefined()),
            'email' => env('ADMIN_EMAIL', 'admin@admin.com'),
            'phone_country' => env('PHONE_COUNTRY', 'ID'),
            'phone' => env('ADMIN_PHONE', '081000111000'),
            'email_verified_at' => Carbon::now(),
            'password' => env('ADMIN_PASSWORD', 'admin12345'),
            'is_active' => true,
        ])->setBranchRelationValue($branch);

        $admin->save();

        $admin->syncRoles(Role::ROLE_ADMIN);
    }
}
