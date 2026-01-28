<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Position;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'IK' => [
                ['name' => 'İK Uzmanı', 'code' => 'IK-UZMAN'],
                ['name' => 'İK Müdürü', 'code' => 'IK-MUDUR'],
            ],
            'URETIM' => [
                ['name' => 'Üretim Operatörü', 'code' => 'UR-OP'],
                ['name' => 'Usta', 'code' => 'UR-USTA'],
            ],
            'MUHASEBE' => [
                ['name' => 'Muhasebeci', 'code' => 'MUH'],
            ],
        ];

        foreach ($data as $deptCode => $positions) {
            $department = Department::where('code', $deptCode)->first();
            if (! $department) continue;

            foreach ($positions as $pos) {
                Position::updateOrCreate(
                    ['code' => $pos['code']],
                    [
                        'department_id' => $department->id,
                        'name' => $pos['name'],
                        'status' => true,
                    ]
                );
            }
        }
    }
}
