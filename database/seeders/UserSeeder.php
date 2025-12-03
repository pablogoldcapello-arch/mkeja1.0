<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Admin user
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@mk.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'profile_photo' => null,
            'phone' => '+254700000001',
            'property_count' => 0,
            'assigned_properties' => null,
            'skills' => null,
            'is_email_verified' => true,
            '2fa_enabled' => false,
            'status' => 'active',
            'dob' => '1990-01-01',
            'gender' => 'male',
            'address' => '1 Admin Street',
            'city' => 'Nairobi',
            'county' => 'Nairobi County',
            'postal_code' => '00100',
            'last_login' => now(),
            'email_verified_at' => now(),
        ]);

        // Landlord
        User::create([
            'name' => 'John Landlord',
            'email' => 'john.landlord@mk.com',
            'password' => Hash::make('landlord123'),
            'role' => 'landlord',
            'profile_photo' => null,
            'phone' => '+254700000002',
            'property_count' => 5,
            'assigned_properties' => null,
            'skills' => null,
            'is_email_verified' => true,
            '2fa_enabled' => false,
            'status' => 'active',
            'dob' => '1985-05-10',
            'gender' => 'male',
            'address' => '123 Main Street',
            'city' => 'Mombasa',
            'county' => 'Mombasa County',
            'postal_code' => '80100',
            'last_login' => now(),
            'email_verified_at' => now(),
        ]);

        // Caretaker / Agent
        User::create([
            'name' => 'Jane Agent',
            'email' => 'jane.agent@mk.com',
            'password' => Hash::make('agent123'),
            'role' => 'caretaker',
            'profile_photo' => null,
            'phone' => '+254700000003',
            'property_count' => 0,
            'assigned_properties' => json_encode([1,2,3]),
            'skills' => null,
            'is_email_verified' => true,
            '2fa_enabled' => true,
            'status' => 'active',
            'dob' => '1992-08-20',
            'gender' => 'female',
            'address' => '456 Agent Avenue',
            'city' => 'Nakuru',
            'county' => 'Nakuru County',
            'postal_code' => '20100',
            'last_login' => now(),
            'email_verified_at' => now(),
        ]);

        // Tenant
        User::create([
            'name' => 'Harry Tenant',
            'email' => 'harry.tenant@mk.com',
            'password' => Hash::make('tenant123'),
            'role' => 'tenant',
            'profile_photo' => null,
            'phone' => '+254700000004',
            'property_count' => 0,
            'assigned_properties' => null,
            'skills' => null,
            'is_email_verified' => true,
            '2fa_enabled' => false,
            'status' => 'active',
            'dob' => '1998-03-15',
            'gender' => 'male',
            'address' => '789 Tenant Road',
            'city' => 'Kisumu',
            'county' => 'Kisumu County',
            'postal_code' => '40100',
            'last_login' => now(),
            'email_verified_at' => now(),
        ]);

        // Tech Support
        User::create([
            'name' => 'Tech Support',
            'email' => 'tech.support@mk.com',
            'password' => Hash::make('tech123'),
            'role' => 'techsupport',
            'profile_photo' => null,
            'phone' => '+254700000005',
            'property_count' => 0,
            'assigned_properties' => null,
            'skills' => json_encode(['networking','software','hardware']),
            'is_email_verified' => true,
            '2fa_enabled' => true,
            'status' => 'active',
            'dob' => '1990-07-07',
            'gender' => 'other',
            'address' => '101 Tech Lane',
            'city' => 'Nairobi',
            'county' => 'Nairobi County',
            'postal_code' => '00100',
            'last_login' => now(),
            'email_verified_at' => now(),
        ]);

        // Service Provider
        User::create([
            'name' => 'Service Pro',
            'email' => 'service.pro@mk.com',
            'password' => Hash::make('service123'),
            'role' => 'service_provider',
            'profile_photo' => null,
            'phone' => '+254700000006',
            'property_count' => 0,
            'assigned_properties' => null,
            'skills' => json_encode(['plumbing','cleaning','electrical']),
            'is_email_verified' => true,
            '2fa_enabled' => false,
            'status' => 'active',
            'dob' => '1988-12-05',
            'gender' => 'female',
            'address' => '202 Service Street',
            'city' => 'Eldoret',
            'county' => 'Uasin Gishu',
            'postal_code' => '30100',
            'last_login' => now(),
            'email_verified_at' => now(),
        ]);
    }
}
