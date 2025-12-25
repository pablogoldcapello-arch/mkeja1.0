<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Property;
use App\Models\Unit;
use App\Models\Tenancy;
use Faker\Factory as Faker;
use Carbon\Carbon;

class MegaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // 1️⃣ Create landlords
        $landlords = User::factory(3)->create([
            'role' => 'landlord'
        ]);

        // 2️⃣ Create agents
        $agents = User::factory(3)->create([
            'role' => 'caretaker'
        ]);

        // 3️⃣ Create tenants
        $tenants = User::factory(20)->create([
            'role' => 'tenant'
        ]);

        // 4️⃣ Create properties for each landlord
        $allProperties = collect();
        $allUnits = collect();

        foreach ($landlords as $landlord) {
            $propertiesCount = rand(1, 3); // Each landlord has 1-3 properties
            for ($i = 0; $i < $propertiesCount; $i++) {
                $property = Property::create([
                    'landlord_id' => $landlord->id,
                    'agent_id' => $agents->random()->id,
                    'title' => $faker->word,
                    'description' => $faker->sentence,
                    'type' => $faker->randomElement(['apartment','house','bedsitter','studio','office','land']),
                    'location' => $faker->city,
                    'coordinates' => $faker->latitude . ',' . $faker->longitude,
                    'units_no' => rand(1,5),
                    'status' => 'available'
                ]);

                $allProperties->push($property);

                // 5️⃣ Create units for each property
                $unitsCount = $property->units_no;
                for ($u = 1; $u <= $unitsCount; $u++) {
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

                    $allUnits->push($unit);
                }
            }
        }

        // 6️⃣ Assign tenants to properties/units via tenancies
        $tenantsShuffled = $tenants->shuffle();

        foreach ($tenantsShuffled as $tenant) {
            // Pick a random property
            $property = $allProperties->random();

            // Randomly assign a unit or leave null
            $assignUnit = rand(0, 1) === 1;
            $unit = $assignUnit && $property->units()->count() > 0
                ? $property->units()->inRandomOrder()->first()
                : null;

            // Random start date within last 60 days
            $startDate = Carbon::now()->subDays(rand(0, 60));

            // Random end date 6-18 months after start
            $endDate = (clone $startDate)->addMonths(rand(6, 18));

            // Randomly terminate some tenancies (~20%)
            $status = rand(0, 4) === 0 ? 'terminated' : 'active';

            Tenancy::create([
                'tenant_id' => $tenant->id,
                'property_id' => $property->id,
                'unit_id' => $unit ? $unit->id : null,
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
                'status' => $status,
            ]);

            // Update unit status if a unit is assigned
            if ($unit) {
                $unit->update(['status' => 'rented']);
            }
        }

        $this->command->info('✅ MegaSeeder finished: landlords, agents, tenants, properties, units, and tenancies created.');
    }
}
