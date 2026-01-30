<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CompanySetting;

class CompanySettingSeeder extends Seeder
{
    public function run(): void
    {
        CompanySetting::firstOrCreate([
            'company_name' => 'Firma Adı',
        ], [
            'default_currency' => 'EUR',
            'default_vat_rate' => 20,
            'quote_prefix' => 'DS',
            'quote_start_number' => 1000,
            'quote_year_format' => 'YY',
        ]);
    }
}
