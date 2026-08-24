<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'category', 'phone', 'email'])]
class Vendor extends Model
{
    public const CATEGORIES = ['Multimedia', 'Venue & F&B', 'Catering', 'Logistics'];

    /** @use HasFactory<Vendor> */
    use HasFactory;

    public function quotationItems(): HasMany
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function categorySlug(): string
    {
        $category = strtolower(trim((string) $this->category));

        foreach (['multimedia', 'venue', 'catering', 'logistics'] as $slug) {
            if (str_contains($category, $slug)) {
                return $slug;
            }
        }

        return '';
    }
}
