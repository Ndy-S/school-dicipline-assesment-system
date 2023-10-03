<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MataPelajaran>
 */
class MataPelajaranFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = \Faker\Factory::create();

        return [
            'nama' => $faker->randomElement(['Bahasa Indonesia', 'Agama', 'Matematika', 'IPA', 'IPS']),
            'kelas' => $faker->numberBetween(1, 6),
        ];
    }
}
