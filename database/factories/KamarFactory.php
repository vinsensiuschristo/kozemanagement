<?php

namespace Database\Factories;

use App\Models\Kamar;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kamar>
 */
class KamarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Kamar::class;
    public function definition(): array
    {
        return [
            'id' => \Illuminate\Support\Str::uuid(),
            'unit_id' => \App\Models\Unit::factory(),
            'tipe_kamar_id' => \App\Models\TipeKamar::factory(),
            'nama' => 'KMR-' . $this->faker->unique()->bothify('###??'),
            'ukuran' => $this->faker->numberBetween(10, 50),
            'lantai' => $this->faker->numberBetween(1, 3),
        ];
    }
}
