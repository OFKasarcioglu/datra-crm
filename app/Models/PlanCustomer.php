<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PlanCustomer extends Model
{
    use HasFactory;

    protected $table = 'plan_customers';

    protected $fillable = [
        /* =========
           TEMEL
        ========= */
        'customer_code',
        'customer_type',
        'name',
        'short_title',
        'title',

        /* =========
           VERGİ
        ========= */
        'tax_number',
        'tax_office',

        /* =========
           ADRES
        ========= */
        'address',
        'country',
        'city',
        'district',
        'postal_code',

        /* =========
           İLETİŞİM
        ========= */
        'phone',
        'fax',
        'email',

        /* =========
           YETKİLİLER
        ========= */
        'contact1_name',
        'contact1_phone',
        'contact1_email',
        'contact2_name',
        'contact2_phone',
        'contact2_email',

        /* =========
           FİNANS
        ========= */
        'currency',
        'credit_limit',

        /* =========
           DURUM / SİSTEM
        ========= */
        'is_active',
        'created_by',
        'note',
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'credit_limit'=> 'decimal:2',
    ];

    /* =======================
       RELATIONSHIPS
    ======================= */

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /* =======================
       ACCESSORS
    ======================= */

    public function getDisplayNameAttribute(): string
    {
        return $this->short_title
            ?? $this->title
            ?? $this->name;
    }

    /* =======================
       SCOPES
    ======================= */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePassive($query)
    {
        return $query->where('is_active', false);
    }
}
