<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesQuoteItem extends Model
{
    protected $fillable = [
        'sales_quote_id',

        'description',
        'drawing_number',
        'payment_term',
        'delivery_date',

        'quantity',
        'unit_weight',
        'total_weight',

        'unit_price',
        'total_price',
    ];

    protected static function booted()
    {
        static::saving(function ($item) {
            // KG * adet
            $item->total_weight = $item->quantity * $item->unit_weight;

            // toplam kg * birim fiyat
            $item->total_price = $item->total_weight * $item->unit_price;
        });

        static::saved(function ($item) {
            $item->salesQuote?->recalculateTotals();
        });

        static::deleted(function ($item) {
            $item->salesQuote?->recalculateTotals();
        });
    }

    /* =======================
        RELATIONS
    ======================= */

    public function salesQuote(): BelongsTo
    {
        return $this->belongsTo(SalesQuote::class);
    }
}
