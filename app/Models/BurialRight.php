<?php

namespace App\Models;

use App\Enums\BurialRightStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BurialRight extends Model
{
    use HasFactory;

    protected $fillable = [
        'family_id',
        'organization_id',
        'plot_id',
        'status',
        'notes',
    ];

    protected $casts = [
        'status' => BurialRightStatus::class,
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function family(): BelongsTo
    {
        return $this->belongsTo(Family::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function invoiceLineItem(): MorphOne
    {
        return $this->morphOne(InvoiceLineItem::class, 'invoiceable');
    }

    public function plot(): BelongsTo
    {
        return $this->belongsTo(Plot::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */
    public function getInvoiceAttribute(): ?Invoice
    {
        return $this->invoiceLineItem?->invoice;
    }

    public function getOwnerAttribute(): Family|Organization
    {
        return $this->family ?? $this->organization;
    }
}
