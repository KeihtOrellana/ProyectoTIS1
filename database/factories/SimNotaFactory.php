<?php

namespace Database\Factories;
use App\Models\SimNota;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SimNota>
 */
class SimNotaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
        'rut_alumno' => 0, // Lo pondremos en el Seeder
        'semestre_inscrito'   => fake()->numberBetween(2024, 2046) . "-" . fake()->randomElement([1, 2]),
        'nota_final' => fake()->randomFloat(1, 4.0, 7.0),
        'fecha_nota' => fake()->dateTimeBetween('-1 year', 'now'),
        'created_at' => now(),
        'updated_at' => now(),
    ];

    }
}
