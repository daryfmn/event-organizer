<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'company_name',
    'address',
    'bank_name',
    'bank_branch',
    'account_number',
    'account_name',
    'phone',
    'email',
    'organizing_fee_percent',
    'ppn_percent',
])]
class CompanySetting extends Model
{
    /** @use HasFactory<CompanySetting> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'organizing_fee_percent' => 'float',
            'ppn_percent' => 'float',
        ];
    }

    public static function current(): ?self
    {
        return static::first();
    }
}
