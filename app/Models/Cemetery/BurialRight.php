<?php

namespace App\Models\Interment;

use App\Models\Cemetery\Mausoleum\Crypt;
use App\Models\Cemetery\Columbarium\Niche;
use App\Models\Cemetery\Plot;
use App\Models\Customer\Family;
use App\Models\Customer\Organization;
use App\Models\Finance\Invoice;
use App\Models\Finance\InvoiceLineItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BurialRight extends Model
{
    use HasFactory;

    protected $fillable = [
        'family_id',
        'organization_id',
        'plot_id',
        'crypt_id',
        'niche_id',
        'invoice_id',
        'invoice_line_item_id',
        'status',
        'issued_at',
        'notes',
    ];

    protected $casts = [
        'issued_at'  => 'date',
        'expires_at' => 'date',
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

    public function plot(): BelongsTo
    {
        return $this->belongsTo(Plot::class);
    }

    public function crypt(): BelongsTo
    {
        return $this->belongsTo(Crypt::class);
    }

    public function niche(): BelongsTo
    {
        return $this->belongsTo(Niche::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function invoiceLineItem(): BelongsTo
    {
        return $this->belongsTo(InvoiceLineItem::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */
    public function getOwnerAttribute(): Family|Organization|null
    {
        return $this->family ?? $this->organization;
    }

    public function getLocationLabelAttribute(): string
    {
        if ($this->plot) {
            return "Plot {$this->plot->lot?->lot_number}{$this->plot->lot?->lot_letter}, Plot #{$this->plot->plot_number}";
        }

        if ($this->crypt) {
            return "Crypt #{$this->crypt->crypt_number} in {$this->crypt->mausoleum?->name}";
        }

        if ($this->niche) {
            return "Niche #{$this->niche->niche_number} in {$this->niche->columbarium?->name}";
        }

        return 'Unassigned';
    }

    public function getIsActiveAttribute(): bool
    {
        return $this->status === 'active' &&
            (! $this->expires_at || $this->expires_at->isFuture());
    }
}
