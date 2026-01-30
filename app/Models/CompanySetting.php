<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanySetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'address',
        'tax_office',
        'tax_number',
        'phone',
        'email',
        'website',
        'logo',
        'default_currency',
        'default_vat_rate',
        'quote_prefix',
        'quote_start_number',
        'quote_year_format',
        'note_1',
        'note_2',
        'note_3',
        'note_4',
        'note_5',
    ];
}
