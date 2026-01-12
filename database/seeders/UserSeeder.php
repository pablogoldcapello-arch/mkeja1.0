<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Property;
use App\Models\Unit;
use App\Models\Tenancy;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();

        // 1️⃣ Admin user
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@mk.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'profile_photo' => null,
            'phone' => '+254700000001',
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

        // 2️⃣ Landlord
        $landlord = User::create([
            'name' => 'John Landlord',
            'email' => 'john.landlord@mk.com',
            'password' => Hash::make('landlord123'),
            'role' => 'landlord',
            'phone' => '+254700000002',
            'property_count' => 0, // will update later
            'is_email_verified' => true,
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

        // 3️⃣ Caretaker / Agent
        $agent = User::create([
            'name' => 'Jane Agent',
            'email' => 'jane.agent@mk.com',
            'password' => Hash::make('agent123'),
            'role' => 'caretaker',
            'phone' => '+254700000003',
            'assigned_properties' => null,
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

        // 4️⃣ Tenant (predefined)
        $tenant = User::create([
            'name' => 'Harry Tenant',
            'email' => 'harry.tenant@mk.com',
            'password' => Hash::make('tenant123'),
            'role' => 'tenant',
            'phone' => '+254700000004',
            'is_email_verified' => true,
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

        // 5️⃣ Optional: other tenants
        $otherTenants = User::factory(10)->create(['role' => 'tenant']);

        $allTenants = collect([$tenant])->merge($otherTenants);

        // 6️⃣ Create properties for the landlord
        $properties = collect();
        $units = collect();
        $numProperties = rand(2, 4);
        for ($p = 1; $p <= $numProperties; $p++) {
            $property = Property::create([
                'landlord_id' => $landlord->id,
                'agent_id' => $agent->id,
                'title' => $faker->word,
                'description' => $faker->sentence,
                'type' => $faker->randomElement(['apartment','house','bedsitter','studio','office','land']),
                'location' => $faker->city,
                'coordinates' => $faker->latitude . ',' . $faker->longitude,
                'units_no' => rand(1,5),
                'status' => 'available'
            ]);
            $properties->push($property);

            // Create units for property
            for ($u = 1; $u <= $property->units_no; $u++) {
                $unit = Unit::create([
                    'property_id' => $property->id,
                    'unit_number' => "Unit-$u",
                    'type' => $faker->randomElement(['apartment','studio','bedsitter']),
                    'deposit' => $faker->numberBetween(1000,5000),
                    'monthly_rent' => $faker->numberBetween(5000,20000),
                    'garbage_fee' => $faker->numberBetween(100,500),
                    'security_fee' => $faker->numberBetween(200,800),
                    'status' => 'vacant',
                    'water_meter' => $faker->ean8,
                    'electricity_meter' => $faker->ean8
                ]);
                $units->push($unit);
            }
        }

        // Update landlord property count
        $landlord->update(['property_count' => $properties->count()]);

        // 7️⃣ Assign tenants to units/properties via tenancies
        // Ensure Harry Tenant is assigned to a unit in one of landlord's properties
        $firstProperty = $properties->first();
        $unitForHarry = $firstProperty->units()->inRandomOrder()->first();

        Tenancy::create([
            'tenant_id' => $tenant->id,
            'property_id' => $firstProperty->id,
            'unit_id' => $unitForHarry->id,
            'start_date' => Carbon::now()->subDays(rand(0,30))->toDateString(),
            'end_date' => Carbon::now()->addMonths(rand(6,12))->toDateString(),
            'status' => 'active'
        ]);

        $unitForHarry->update(['status' => 'rented']);

        // Assign other tenants randomly
        foreach ($otherTenants as $otherTenant) {
            $property = $properties->random();
            $assignUnit = rand(0,1) === 1;
            $unit = $assignUnit && $property->units()->count() > 0
                ? $property->units()->where('status','vacant')->inRandomOrder()->first()
                : null;

            Tenancy::create([
                'tenant_id' => $otherTenant->id,
                'property_id' => $property->id,
                'unit_id' => $unit ? $unit->id : null,
                'start_date' => Carbon::now()->subDays(rand(0,60))->toDateString(),
                'end_date' => Carbon::now()->addMonths(rand(6,18))->toDateString(),
                'status' => rand(0,4) === 0 ? 'terminated' : 'active'
            ]);

            if ($unit) {
                $unit->update(['status' => 'rented']);
            }
        }

        // 8️⃣ Service Providers (predefined for auto-login)
        $serviceProvidersData = [
        [
            'name' => 'Peter Plumber',
            'email' => 'peter.plumber@mk.com',
            'password' => Hash::make('provider123'),
            'role' => 'service_provider',
            'phone' => '+254700000005',
            'skills' => json_encode(['plumbing', 'cleaning']),
            'is_email_verified' => true,
            'status' => 'active',
            'dob' => '1990-02-15',
            'gender' => 'male',
            'address' => '12 Service Road',
            'city' => 'Nairobi',
            'county' => 'Nairobi County',
            'postal_code' => '00100',
            'last_login' => now(),
            'email_verified_at' => now(),
        ],
        [
            'name' => 'Emma Electrician',
            'email' => 'emma.electrician@mk.com',
            'password' => Hash::make('provider123'),
            'role' => 'service_provider',
            'phone' => '+254700000006',
            'skills' => json_encode(['electrical', 'painting']),
            'is_email_verified' => true,
            'status' => 'active',
            'dob' => '1992-06-10',
            'gender' => 'female',
            'address' => '34 Service Lane',
            'city' => 'Mombasa',
            'county' => 'Mombasa County',
            'postal_code' => '80100',
            'last_login' => now(),
            'email_verified_at' => now(),
        ],
        [
            'name' => 'Sam Gardener',
            'email' => 'sam.gardener@mk.com',
            'password' => Hash::make('provider123'),
            'role' => 'service_provider',
            'phone' => '+254700000007',
            'skills' => json_encode(['gardening', 'security']),
            'is_email_verified' => true,
            'status' => 'active',
            'dob' => '1988-09-25',
            'gender' => 'male',
            'address' => '56 Garden Street',
            'city' => 'Nakuru',
            'county' => 'Nakuru County',
            'postal_code' => '20100',
            'last_login' => now(),
            'email_verified_at' => now(),
        ],
        ];

        // Loop and create service providers
        foreach ($serviceProvidersData as $spData) {
        User::create($spData);
        }


    }
}
