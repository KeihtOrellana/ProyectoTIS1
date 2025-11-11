<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\SimProfesor;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SimProfesor>
 */
class SimProfesorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */


    public function definition(): array
    {
    return [
        'rut' => fake()->unique()->numberBetween(7000000, 12000000),
        'nombre' => fake()->firstName() . " " . fake()->lastName(),
        'created_at' => now(),
        'updated_at' => now(),
    ];
    }
}
