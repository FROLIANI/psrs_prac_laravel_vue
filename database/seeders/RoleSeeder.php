<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->upsert([
            ['id' => 1, 'name' => 'admin',  'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'editor', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'viewer', 'created_at' => now(), 'updated_at' => now()],
        ], ['id'], ['name', 'updated_at']);
    }
}
