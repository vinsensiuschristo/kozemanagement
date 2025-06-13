<?php

namespace Database\Factories;

use App\Models\FotoUnit;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FotoUnit>
 */
class FotoUnitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = FotoUnit::class;
    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'unit_id' => Unit::factory(),
            'kategori' => $this->faker->randomElement(['depan', 'dalam', 'jalan']),
            'path' => 'unit/foto/' . $this->faker->uuid . '.jpg',
        ];
    }
}
