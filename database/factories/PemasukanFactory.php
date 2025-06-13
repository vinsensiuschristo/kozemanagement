<?php

namespace Database\Factories;

use App\Models\Pemasukan;
use App\Models\Unit;
use App\Models\Penghuni;
use App\Models\Kamar;
use App\Models\User;
use App\Models\LogPenghuni;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pemasukan>
 */
class PemasukanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Pemasukan::class;

    public function definition(): array
    {
        return [
            'unit_id' => Unit::factory(),
            'penghuni_id' => Penghuni::factory(),
            'kamar_id' => Kamar::factory(),
            'checkin_id' => LogPenghuni::factory(),
            'tanggal' => $this->faker->date(),
            'jumlah' => $this->faker->numberBetween(500000, 3000000),
            'deskripsi' => 'Pembayaran sewa kamar',
            'bukti' => null,
            'is_konfirmasi' => $this->faker->boolean(),
            'created_by' => 1,
        ];
    }
}
