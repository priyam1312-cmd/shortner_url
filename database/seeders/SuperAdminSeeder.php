<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create SuperAdmin using raw SQL
        DB::statement("
            INSERT INTO users (name, email, password, role, company_id, created_at, updated_at)
            VALUES ('Super Admin', 'superadmin@example.com', ?, 'SuperAdmin', NULL, NOW(), NOW())
            ON DUPLICATE KEY UPDATE email = email
        ", [Hash::make('password')]);
    }
}

