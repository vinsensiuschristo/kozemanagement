<?php

namespace Database\Factories;

use App\Models\Penghuni;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Penghuni>
 */
class PenghuniFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Penghuni::class;

    public function definition(): array
    {
        return [
            'kode' => 'PHN-' . strtoupper(Str::random(8)),
            'nama' => $this->faker->name,
            'tempat_lahir' => $this->faker->city,
            'tanggal_lahir' => $this->faker->date(),
            'agama' => $this->faker->randomElement(['Islam', 'Kristen', 'Hindu', 'Budha']),
            'no_telp' => $this->faker->phoneNumber,
            'email' => $this->faker->safeEmail,
            'kontak_darurat' => $this->faker->phoneNumber,
            'hubungan_kontak_darurat' => 'Keluarga',
            'kendaraan' => $this->faker->optional()->bothify('B #### ??'),
            'foto_ktp' => null,
            'referensi' => $this->faker->name,
            'status' => $this->faker->randomElement(['In', 'Out']),
        ];
    }
}
