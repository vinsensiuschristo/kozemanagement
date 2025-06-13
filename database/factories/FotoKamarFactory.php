<?php

namespace Database\Factories;

use App\Models\FotoKamar;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FotoKamar>
 */
class FotoKamarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = FotoKamar::class;
    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'kamar_id' => \App\Models\Kamar::factory(),
            'kategori' => $this->faker->randomElement(['depan', 'dalam', 'kamar_mandi']),
            'path' => 'kamar/' . Str::uuid() . '.jpg',
        ];
    }
}
