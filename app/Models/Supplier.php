<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_code',
        'name',
        'short_title',
        'title',
        'tax_number',
        'tax_office',
        'address',
        'country',
        'city',
        'district',
        'postal_code',
        'phone',
        'fax',
        'email',
        'contact1_name',
        'contact1_phone',
        'contact1_email',
        'contact2_name',
        'contact2_phone',
        'contact2_email',
        'currency',
        'payment_term',
        'credit_limit',
        'is_active',
        'created_by',
        'note',
    ];

    protected $casts = [
        'is_active'     => 'boolean',
        'credit_limit' => 'decimal:2',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->short_title
            ?? $this->title
            ?? $this->name;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
