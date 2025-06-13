<?php

namespace Database\Factories;

use App\Models\Pengeluaran;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pengeluaran>
 */
class PengeluaranFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Pengeluaran::class;

    public function definition(): array
    {
        return [
            'unit_id' => Unit::factory(),
            'tanggal' => $this->faker->date(),
            'jumlah' => $this->faker->numberBetween(100000, 2000000),
            'kategori' => $this->faker->randomElement(['Listrik', 'Air', 'Perbaikan', 'Lainnya']),
            'deskripsi' => $this->faker->sentence(),
            'bukti' => null,
            'is_konfirmasi' => true,
            'created_by' => 1,
        ];
    }
}
