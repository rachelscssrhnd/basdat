<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\Cabang;
use App\Models\JenisTes;
use App\Models\ParameterTes;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $adminRole = Role::firstOrCreate(['nama_role' => 'admin']);
        $userRole = Role::firstOrCreate(['nama_role' => 'user']);

        // Create admin user
        User::firstOrCreate(
            ['username' => 'admin'],
            [
                'password_hash' => bcrypt('admin123'),
                'role_id' => $adminRole->role_id
            ]
        );

        // Create branches
        Cabang::firstOrCreate(
            ['nama_cabang' => 'Jakarta Central'],
            ['alamat' => 'Jl. Sudirman No. 123, Jakarta Pusat']
        );

        Cabang::firstOrCreate(
            ['nama_cabang' => 'Jakarta Selatan'],
            ['alamat' => 'Jl. Pondok Indah No. 456, Jakarta Selatan']
        );

        // Create lab tests
        $basicHealth = JenisTes::firstOrCreate(
            ['nama_tes' => 'Basic Health Panel'],
            [
                'deskripsi' => '12 tests included - Complete blood count, basic metabolic panel',
                'harga' => 89000,
                'persiapan_khusus' => 'Fasting required for 8-12 hours'
            ]
        );

        $metabolicPanel = JenisTes::firstOrCreate(
            ['nama_tes' => 'Complete Metabolic Panel'],
            [
                'deskripsi' => '20 tests included - Comprehensive metabolic assessment',
                'harga' => 129000,
                'persiapan_khusus' => 'Fasting required for 8-12 hours'
            ]
        );

        $immunityCheck = JenisTes::firstOrCreate(
            ['nama_tes' => 'Immunity Checkup'],
            [
                'deskripsi' => '8 tests included - Immune system assessment',
                'harga' => 75000,
                'persiapan_khusus' => 'No special preparation required'
            ]
        );

        // Create test parameters
        ParameterTes::firstOrCreate(
            ['nama_parameter' => 'Hemoglobin'],
            [
                'tes_id' => $basicHealth->tes_id,
                'satuan' => 'g/dL'
            ]
        );

        ParameterTes::firstOrCreate(
            ['nama_parameter' => 'WBC Count'],
            [
                'tes_id' => $basicHealth->tes_id,
                'satuan' => '×10³/µL'
            ]
        );

        ParameterTes::firstOrCreate(
            ['nama_parameter' => 'Fasting Glucose'],
            [
                'tes_id' => $metabolicPanel->tes_id,
                'satuan' => 'mg/dL'
            ]
        );

        ParameterTes::firstOrCreate(
            ['nama_parameter' => 'Total Cholesterol'],
            [
                'tes_id' => $metabolicPanel->tes_id,
                'satuan' => 'mg/dL'
            ]
        );
    }
}
