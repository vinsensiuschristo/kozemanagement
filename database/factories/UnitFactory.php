<?php

namespace Database\Factories;

use App\Models\Unit;
use App\Models\Owner;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Unit>
 */
class UnitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Unit::class;

    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'id_owner' => Owner::factory(),
            'nomor_kontrak' => 'KTR-' . strtoupper(Str::random(8)),
            'tanggal_awal_kontrak' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'tanggal_akhir_kontrak' => $this->faker->dateTimeBetween('now', '+2 years'),
            'nama_cluster' => 'CLUSTER-' . strtoupper(Str::random(6)),
            'multi_tipe' => $this->faker->boolean(),
            'disewakan_untuk' => $this->faker->randomElement(['putra', 'putri', 'campur']),
            'deskripsi' => $this->faker->paragraph(),
            'tahun_dibangun' => $this->faker->year(),
            'user_id' => User::factory()->create()->id,
        ];
    }
}
