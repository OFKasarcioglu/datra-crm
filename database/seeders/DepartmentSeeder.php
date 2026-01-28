<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            [
                'name' => 'İnsan Kaynakları',
                'code' => 'IK',
                'description' => 'Personel, izin, özlük ve bordro işlemleri',
                'status' => true,
            ],
            [
                'name' => 'Üretim',
                'code' => 'URETIM',
                'description' => 'Üretim planlama ve saha operasyonları',
                'status' => true,
            ],
            [
                'name' => 'Planlama',
                'code' => 'PLAN',
                'description' => 'İş emirleri ve üretim planlaması',
                'status' => true,
            ],
            [
                'name' => 'Depo & Stok',
                'code' => 'DEPO',
                'description' => 'Hammadde, mamül ve stok yönetimi',
                'status' => true,
            ],
            [
                'name' => 'Satın Alma',
                'code' => 'SATINALMA',
                'description' => 'Tedarikçi ve satın alma süreçleri',
                'status' => true,
            ],
            [
                'name' => 'Satış',
                'code' => 'SATIS',
                'description' => 'Teklif, sipariş ve müşteri ilişkileri',
                'status' => true,
            ],
            [
                'name' => 'Muhasebe',
                'code' => 'MUHASEBE',
                'description' => 'Fatura, cari ve finansal işlemler',
                'status' => true,
            ],
            [
                'name' => 'Bakım & Teknik',
                'code' => 'BAKIM',
                'description' => 'Araç, makine ve tesis bakım süreçleri',
                'status' => true,
            ],
        ];

        foreach ($departments as $department) {
            Department::updateOrCreate(
                ['code' => $department['code']],
                $department
            );
        }
    }
}
