<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisTes;
use App\Models\ParameterTes;

class ParameterTesSeeder extends Seeder
{
    public function run(): void
    {
        $definitions = [
            'Tes Rontgen Gigi (Dental I CR)' => [
                ['nama_parameter' => 'Temuan', 'satuan' => null],
                ['nama_parameter' => 'Kesimpulan', 'satuan' => null],
            ],
            'Tes Rontgen Gigi (Panoramic)' => [
                ['nama_parameter' => 'Temuan', 'satuan' => null],
                ['nama_parameter' => 'Kesimpulan', 'satuan' => null],
            ],
            "Tes Rontgen Gigi (Water's Foto)" => [
                ['nama_parameter' => 'Temuan', 'satuan' => null],
                ['nama_parameter' => 'Kesimpulan', 'satuan' => null],
            ],
            'Tes Urine' => [
                ['nama_parameter' => 'Warna', 'satuan' => null],
                ['nama_parameter' => 'Kejernihan', 'satuan' => null],
                ['nama_parameter' => 'pH', 'satuan' => null],
                ['nama_parameter' => 'Berat Jenis', 'satuan' => null],
                ['nama_parameter' => 'Protein', 'satuan' => null],
                ['nama_parameter' => 'Glukosa', 'satuan' => null],
                ['nama_parameter' => 'Keton', 'satuan' => null],
                ['nama_parameter' => 'Nitrit', 'satuan' => null],
                ['nama_parameter' => 'Leukosit', 'satuan' => null],
                ['nama_parameter' => 'Eritrosit', 'satuan' => null],
            ],
            'Tes Kehamilan (Anti-Rubella lgG)' => [
                ['nama_parameter' => 'Anti-Rubella IgG', 'satuan' => 'AU/mL'],
                ['nama_parameter' => 'Interpretasi', 'satuan' => null],
            ],
            'Tes Kehamilan (Anti-CMV lgG)' => [
                ['nama_parameter' => 'Anti-CMV IgG', 'satuan' => 'AU/mL'],
                ['nama_parameter' => 'Interpretasi', 'satuan' => null],
            ],
            'Tes Kehamilan (Anti-HSV1 lgG)' => [
                ['nama_parameter' => 'Anti-HSV1 IgG', 'satuan' => 'AU/mL'],
                ['nama_parameter' => 'Interpretasi', 'satuan' => null],
            ],
            'Tes Darah (Hemoglobin)' => [
                ['nama_parameter' => 'Hemoglobin', 'satuan' => 'g/dL'],
            ],
            'Tes Darah (Golongan Darah)' => [
                ['nama_parameter' => 'ABO', 'satuan' => null],
                ['nama_parameter' => 'Rhesus', 'satuan' => null],
            ],
            'Tes Darah (Agregasi Trombosit)' => [
                ['nama_parameter' => 'Agregasi ADP', 'satuan' => '%'],
                ['nama_parameter' => 'Agregasi Epinefrin', 'satuan' => '%'],
                ['nama_parameter' => 'Agregasi Kolagen', 'satuan' => '%'],
                ['nama_parameter' => 'Agregasi Ristocetin', 'satuan' => '%'],
            ],
        ];

        foreach ($definitions as $testName => $params) {
            $test = JenisTes::where('nama_tes', $testName)->first();
            if (!$test) {
                continue;
            }

            foreach ($params as $p) {
                ParameterTes::firstOrCreate(
                    [
                        'tes_id' => $test->tes_id,
                        'nama_parameter' => $p['nama_parameter'],
                    ],
                    [
                        'satuan' => $p['satuan'],
                    ]
                );
            }
        }
    }
}
