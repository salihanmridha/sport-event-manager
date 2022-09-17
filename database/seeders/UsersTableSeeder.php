<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'id'             => 1,
                // 'name'           => 'Admin',
                'email'          => 'admin@admin.com',
                'password'       => bcrypt('password'),
                'remember_token' => null,
                'locale'         => '',
                'first_name'         => 'x',
                'last_name'         => 'x',
                'status'         => 'x',
                'bio'         => 'x',
                'phone'         => '0',
                'phone_code'         => '0',
                'is_notify_email'         => '0',
                'is_notify_sms'         => '0',
                'is_notify_push'         => '0',
                'is_marketing'         => '0',
            ],
        ];

        User::insert($users);
    }
}
