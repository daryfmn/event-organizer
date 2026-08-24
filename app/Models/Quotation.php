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

    public static function generateNumber(): string
    {
        $year = now()->format('Y');

        $maxSeq = (int) static::where('quotation_number', 'like', "%/{$year}")
            ->get()
            ->map(fn (self $quotation) => (int) strtok($quotation->quotation_number, '/'))
            ->max();

        $romans = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        $roman = $romans[(int) now()->format('n') - 1];

        return str_pad((string) ($maxSeq + 1), 4, '0', STR_PAD_LEFT).'/QUOT/HCN/'.$roman.'/'.$year;
    }

    public function recalculate(): void
    {
        $subtotal = round((float) $this->items()->sum('total'), 2);
        $organizingFee = round($subtotal * $this->organizing_fee_percent / 100, 2);
        $total = $subtotal + $organizingFee;
        $ppn = round($total * $this->ppn_percent / 100, 2);

        $this->forceFill([
            'subtotal' => $subtotal,
            'organizing_fee' => $organizingFee,
            'total' => $total,
            'ppn' => $ppn,
            'grand_total' => $total + $ppn,
        ])->save();
    }

    public static function rupiah(float|string|null $amount): string
    {
        return 'Rp '.number_format((float) $amount, 0, ',', '.');
    }
}
