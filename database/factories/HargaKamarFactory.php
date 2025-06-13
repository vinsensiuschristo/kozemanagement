<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HargaKamar>
 */
class HargaKamarFactory extends Factory
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
            'tipe_kamar_id' => \App\Models\TipeKamar::factory(),
            'harga_perbulan' => $this->faker->numberBetween(500000, 2000000),
            'harga_perminggu' => $this->faker->numberBetween(200000, 1000000),
            'harga_perhari' => $this->faker->numberBetween(100000, 500000),
            'minimal_deposit' => $this->faker->numberBetween(0, 500000),
        ];
    }
}
