<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KetersediaanKamar>
 */
class KetersediaanKamarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid,
            'kamar_id' => \App\Models\Kamar::factory(),
            'status' => $this->faker->randomElement(['kosong', 'booked', 'terisi']),
        ];
    }
}
