<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FasilitasUnit;
use App\Models\Unit;
use App\Models\Fasilitas;
use Illuminate\Support\Str;

class FasilitasUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = Unit::all();
        $fasilitas = Fasilitas::pluck('id')->toArray();

        foreach ($units as $unit) {
            $randomFasilitas = collect($fasilitas)->random(rand(3, 6));

            foreach ($randomFasilitas as $fasilitas_id) {
                FasilitasUnit::create([
                    'id' => Str::uuid(),
                    'unit_id' => $unit->id,
                    'fasilitas_id' => $fasilitas_id,
                ]);
            }
        }
    }
}
