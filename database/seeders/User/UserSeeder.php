<?php

namespace Database\Seeders\User;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'firstname'         => "Test",
                'lastname'          => "User",
                'email'             => "user@appdevs.net",
                'username'          => "testuser",
                'status'            => true,
                'refferal_user_id'  => "4000",
                'password'          => Hash::make("appdevs"),
                'email_verified'    => true,
                'sms_verified'      => true,
                'kyc_verified'      => true,
                'created_at'        => now(),
            ],
            [
                'firstname'         => "User",
                'lastname'          => "Test",
                'email'             => "user2@appdevs.net",
                'username'          => "usertest",
                'status'            => true,
                'refferal_user_id'  => "4001",
                'password'          => Hash::make("appdevs"),
                'email_verified'    => true,
                'sms_verified'      => true,
                'kyc_verified'      => true,
                'created_at'        => now(),
            ],
        ];

        User::insert($data);
    }
}
