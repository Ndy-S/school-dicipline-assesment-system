<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Siswa>
 */
class SiswaFactory extends Factory
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
            'nama' => $faker->name,
            'nis' => $faker->unique()->randomNumber(6),
            'kelas' => $faker->numberBetween(1, 6),
            'gender' => $faker->randomElement(['L', 'P']),
        ];
    }
}
