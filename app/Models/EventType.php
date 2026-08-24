<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name'])]
class EventType extends Model
{
    /** @use HasFactory<EventType> */
    use HasFactory;

    public function typeClass(): string
    {
        $slug = strtolower(trim(str_replace('event', '', $this->name)));

        return 'type-'.preg_replace('/[^a-z0-9]+/', '-', $slug);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
