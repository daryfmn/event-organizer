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
}
