<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesQuote extends Model
{
    protected $fillable = [
        'quote_no',
        'quote_date',

        'customer_id',
        'customer_name',
        'contact_person',
        'contact_phone',
        'contact_email',

        'currency',
        'vat_rate',

        'sub_total',
        'vat_amount',
        'grand_total',

        'status',

        'note_1',
        'note_2',
        'note_3',
        'note_4',
        'note_5',

        'created_by',
    ];

    protected $casts = [
    'quote_date' => 'date',
];

    /* =======================
        RELATIONS
    ======================= */

    public function items(): HasMany
    {
        return $this->hasMany(SalesQuoteItem::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(PlanCustomer::class, 'customer_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /* =======================
        HESAPLAMA
    ======================= */

    public function recalculateTotals(): void
    {
        $subTotal = $this->items->sum('total_price');

        $vatAmount = ($subTotal * $this->vat_rate) / 100;
        $grandTotal = $subTotal + $vatAmount;

        $this->updateQuietly([
            'sub_total'   => $subTotal,
            'vat_amount'  => $vatAmount,
            'grand_total' => $grandTotal,
        ]);
    }

    protected static function booted()
{
    static::creating(function ($quote) {

        if ($quote->quote_no) {
            return;
        }

        $setting = \App\Models\CompanySetting::first();

        $year = $setting->quote_year_format === 'YYYY'
            ? now()->format('Y')
            : now()->format('y');

        $prefix = $setting->quote_prefix ?? 'DS';

        $lastNumber = self::whereYear('created_at', now()->year)
            ->max('id');

        $sequence = ($lastNumber ?? $setting->quote_start_number ?? 1000) + 1;

        $quote->quote_no = "{$prefix}{$year}-{$sequence}";
    });
}

}
