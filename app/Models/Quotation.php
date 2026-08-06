<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'event_id',
    'signed_by',
    'quotation_number',
    'variant_label',
    'variant_start_date',
    'variant_end_date',
    'organizing_fee_percent',
    'ppn_percent',
    'subtotal',
    'organizing_fee',
    'total',
    'ppn',
    'grand_total',
    'notes',
    'is_approved',
])]
class Quotation extends Model
{
    /** @use HasFactory<Quotation> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'variant_start_date' => 'date',
            'variant_end_date' => 'date',
            'organizing_fee_percent' => 'float',
            'ppn_percent' => 'float',
            'is_approved' => 'boolean',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function signedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'signed_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(QuotationItem::class)->orderBy('sort_order');
    }
}
