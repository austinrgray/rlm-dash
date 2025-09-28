<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class InvoiceLineItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'invoiceable_type',
        'invoiceable_id',
        'description',
        'amount',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function invoiceable(): MorphTo
    {
        return $this->morphTo();
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */
    public function getDisplayLabelAttribute(): string
    {
        if ($this->description) {
            return $this->description;
        }

        return match (true) {
            $this->invoiceable instanceof BurialRight => 'Burial Right',
            $this->invoiceable instanceof IntermentRecord => 'Interment Service',
            $this->invoiceable instanceof Order => "Order #{$this->invoiceable->id}",
            default => class_basename($this->invoiceable) . " #{$this->invoiceable?->id}",
        };
    }
}
