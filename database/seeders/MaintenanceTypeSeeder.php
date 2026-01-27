<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MaintenanceType;

class MaintenanceTypeSeeder extends Seeder
{
    public function run(): void
    {
        $maintenanceTypes = [
            // 🔧 Periyodik Bakımlar
            'Periyodik Bakım',
            'Yağ Değişimi',
            'Yağ + Filtre Değişimi',
            'Hava Filtresi Değişimi',
            'Yakıt Filtresi Değişimi',
            'Polen Filtresi Değişimi',

            // 🚗 Mekanik
            'Fren Bakımı',
            'Balata Değişimi',
            'Disk Değişimi',
            'Debriyaj Bakımı',
            'Şanzıman Bakımı',

            // 🛞 Lastik & Yürüyen
            'Lastik Değişimi',
            'Rot Balans',
            'Amortisör Bakımı',

            // 🔋 Elektrik
            'Akü Değişimi',
            'Elektrik Arızası',

            // 📄 Zorunlu / Resmi
            'Muayene',
            'Sigorta',
            'Kasko',
            'Egzoz Emisyon',

            // ⚠️ Arıza
            'Arıza Onarım',
            'Acil Servis Müdahalesi',
        ];

        foreach ($maintenanceTypes as $typeName) {
            MaintenanceType::updateOrCreate(
                ['name' => $typeName],
                ['is_active' => true]
            );
        }
    }
}