<?php

namespace Database\Factories;

use App\Models\Fasilitas;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Fasilitas>
 */
class FasilitasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Fasilitas::class;
    public function definition(): array
    {
        return [
            'nama' => $this->faker->unique()->word(),
            'tipe' => $this->faker->randomElement(['umum', 'kamar', 'kamar_mandi', 'parkir']),
        ];
    }
}
