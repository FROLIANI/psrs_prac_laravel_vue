<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name'     => 'Admin User',
                'password' => 'secret12366',
                'role_id'  => 1,
            ]
        );

        User::updateOrCreate(
            ['email' => 'editor@gmail.com'],
            [
                'name'     => 'Editor User',
                'password' => 'secret12366',
                'role_id'  => 2,
            ]
        );
    }
}
