<?php

namespace Database\Factories;
use App\Models\SimAlumno;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SimAlumno>
 */
class SimAlumnoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
        'rut' => fake()->unique()->numberBetween(10000000, 25000000),
        'nombre' => fake()->name(),
        'created_at' => now(),
        'updated_at' => now(),
    ];

    }
}
