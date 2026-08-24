<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['client_id', 'event_type_id', 'created_by', 'title', 'description', 'venue', 'start_date', 'end_date', 'status'])]
class Event extends Model
{
    /** @use HasFactory<Event> */
    use HasFactory;

    public const STATUSES = ['draft', 'sent', 'approved', 'done'];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function eventType(): BelongsTo
    {
        return $this->belongsTo(EventType::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function quotations(): HasMany
    {
        return $this->hasMany(Quotation::class);
    }

    public function statusLabel(): string
    {
        return ucfirst($this->status);
    }

    public function dateRangeLabel(): string
    {
        $start = $this->start_date;
        $end = $this->end_date;

        if ($start->isSameDay($end)) {
            return $start->format('d M Y');
        }

        if ($start->format('m Y') === $end->format('m Y')) {
            return $start->format('d').' – '.$end->format('d M Y');
        }

        if ($start->format('Y') === $end->format('Y')) {
            return $start->format('d M').' – '.$end->format('d M Y');
        }

        return $start->format('d M Y').' – '.$end->format('d M Y');
    }
}
