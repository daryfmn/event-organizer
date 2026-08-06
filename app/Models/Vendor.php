<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'category', 'phone', 'email'])]
class Vendor extends Model
{
    /** @use HasFactory<Vendor> */
    use HasFactory;

    public function quotationItems(): HasMany
    {
        return $this->hasMany(QuotationItem::class);
    }
}
