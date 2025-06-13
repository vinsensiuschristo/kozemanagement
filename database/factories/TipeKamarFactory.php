<?php

namespace Database\Factories;

use App\Models\TipeKamar;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TipeKamar>
 */
class TipeKamarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = TipeKamar::class;
    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'unit_id' => \App\Models\Unit::factory(),
            'nama_tipe' => 'Tipe ' . $this->faker->randomLetter(),
        ];
    }
}
