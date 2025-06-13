<?php

namespace Database\Factories;

use App\Models\AlamatUnit;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AlamatUnit>
 */
class AlamatUnitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = AlamatUnit::class;
    public function definition(): array
    {
        return [
            'unit_id' => Unit::factory(),
            'alamat' => $this->faker->streetAddress(),
            'provinsi' => $this->faker->randomElement(['DKI Jakarta', 'Banten']),
            'kabupaten' => $this->faker->city(),
            'kecamatan' => $this->faker->citySuffix(),
            'deskripsi' => $this->faker->optional()->paragraph(),
        ];
    }
}
