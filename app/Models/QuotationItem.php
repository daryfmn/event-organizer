<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'quotation_id',
    'vendor_id',
    'section',
    'sub_section',
    'description',
    'item_detail',
    'qty',
    'unit',
    'length',
    'length_unit',
    'price',
    'total',
    'sort_order',
])]
class QuotationItem extends Model
{
    /** @use HasFactory<QuotationItem> */
    use HasFactory;

    public const SECTIONS = [
        'pre_event',
        'secretariat',
        'team_management',
        'venue',
        'production',
    ];

    public const SECTION_LABELS = [
        'pre_event' => 'Pre Event',
        'secretariat' => 'Secretariat',
        'team_management' => 'Team Management',
        'venue' => 'Venue',
        'production' => 'Production',
    ];

    protected function casts(): array
    {
        return [
            'qty' => 'float',
            'length' => 'float',
            'price' => 'float',
            'total' => 'float',
        ];
    }

    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }
}
