<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;
    protected $model = \App\Models\User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $roles = ['admin', 'landlord', 'caretaker', 'tenant', 'techsupport', 'service_provider'];

        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password123'), // default password
            'profile_photo' => null,
            'phone' => $this->faker->phoneNumber(),
            'role' => $this->faker->randomElement($roles),
            'property_count' => 0,
            'assigned_properties' => null,
            'skills' => null,
            'is_email_verified' => true,
            '2fa_enabled' => false,
            'status' => 'active',
            'dob' => $this->faker->date(),
            'gender' => $this->faker->randomElement(['male', 'female', 'other']),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'county' => $this->faker->state(),
            'postal_code' => $this->faker->postcode(),
            'last_login' => now(),
            'email_verified_at' => now(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
