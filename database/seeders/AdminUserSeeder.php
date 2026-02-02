<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@royalmaidshub.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        echo "✅ Admin user created successfully!\n";
        echo "📧 Email: admin@royalmaidshub.com\n";
        echo "🔑 Password: password\n";
    }
}
