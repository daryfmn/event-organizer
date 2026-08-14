<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['company_name', 'pic_name', 'phone', 'email', 'address'])]
class Client extends Model
{
    /** @use HasFactory<Client> */
    use HasFactory;

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function initials(): string
    {
        $words = collect(preg_split('/\s+/', trim($this->company_name)))
            ->reject(fn (string $word) => strlen(preg_replace('/[^A-Za-z0-9]/', '', $word)) <= 3)
            ->map(fn (string $word) => mb_strtoupper(mb_substr(preg_replace('/[^A-Za-z0-9]/', '', $word), 0, 1)))
            ->take(2)
            ->implode('');

        return $words !== ''
            ? $words
            : mb_strtoupper(mb_substr($this->company_name, 0, 1) ?: '?');
    }
}
