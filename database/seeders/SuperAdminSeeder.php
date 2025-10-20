<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Check if a super admin already exists
        if (!User::where('role', 'user')->exists()) {
            User::create([
                'name' => 'Super Admin',
                'email' => 'user@example.com',
                'password' => Hash::make('password123'), // 🔒 Change this later
                'role' => 'user',
            ]);

            $this->command->info('✅ Super Admin created: superadmin@example.com / password123');
        } else {
            $this->command->info('ℹ️ Super Admin already exists. Skipping creation.');
        }
    }
}
