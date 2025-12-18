<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1️⃣ Create a service provider user
        $providerId = DB::table('users')->insertGetId([
            'name' => 'John Doe',
            'email' => 'provider@example.com',
            'password' => Hash::make('password123'), // Always hash passwords
            'phone' => '0712345678',
            'role' => 'service_provider',
            'status' => 'active',
            'is_email_verified' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2️⃣ Populate services table with this provider
        $services = [
            [
                'name' => 'Plumbing',
                'description' => 'All plumbing services',
                'provider_id' => $providerId,
                'default_rate' => 500.00,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Electrical',
                'description' => 'Electrical maintenance and repairs',
                'provider_id' => $providerId,
                'default_rate' => 700.00,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Cleaning',
                'description' => 'House and office cleaning services',
                'provider_id' => $providerId,
                'default_rate' => 300.00,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Gardening',
                'description' => 'Garden maintenance services',
                'provider_id' => $providerId,
                'default_rate' => 400.00,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Security',
                'description' => 'Security personnel services',
                'provider_id' => $providerId,
                'default_rate' => 600.00,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('services')->insert($services);
    }
}
