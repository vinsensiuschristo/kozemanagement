<?php

namespace Database\Factories;

use App\Models\LogPenghuni;
use App\Models\Penghuni;
use App\Models\Kamar;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LogPenghuni>
 */
class LogPenghuniFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = LogPenghuni::class;

    public function definition(): array
    {
        return [
            'penghuni_id' => Penghuni::factory(),
            'kamar_id' => Kamar::factory(),
            'tanggal' => $this->faker->date(),
            'status' => $this->faker->randomElement(['checkin', 'checkout']),
            'created_by' => User::factory(),
        ];
    }
}
