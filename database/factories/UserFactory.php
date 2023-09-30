<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $token = '';
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        for ($i = 0; $i < 4; $i++) {
            $token .= $characters[rand(0, strlen($characters) - 1)];
        }
        $roles = ['Admin', 'Guru', 'Orang Tua', 'Kepala Sekolah'];

        return [
            'token' => $token,
            'password' => $token,
            'peran' => $roles[array_rand($roles)],
            'nama' => fake()->name(),
            'image_path' => 'default.png',
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
