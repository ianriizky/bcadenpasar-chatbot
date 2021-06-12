<?php

namespace Database\Seeders;

use App\Enum\Gender;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'username' => env('ADMIN_USERNAME', 'admin'),
            'fullname' => env('ADMIN_FULLNAME', 'Administrator'),
            'gender' => Gender::undefined(),
            'email' => env('ADMIN_EMAIL', 'admin@admin.com'),
            'phone_country' => env('PHONE_COUNTRY', 'ID'),
            'phone' => env('ADMIN_PHONE', '081000111000'),
            'email_verified_at' => Carbon::now(),
            'password' => env('ADMIN_PASSWORD', 'admin12345'),
        ]);
    }
}
