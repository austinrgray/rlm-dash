<?php

namespace App\Models\Finance;

use App\Enums\Finance\InvoiceStatus;
use App\Models\Customer\Family;
use App\Models\Customer\Organization;
use App\Models\Cemetery\InternalCemetery;
use App\Models\Memorials\InternalMonumentRetailer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'internal_cemetery_id',
        'internal_monument_retailer_id',
        'family_id',
        'organization_id',
        'status',
        'invoice_date',
        'due_date',
        'subtotal',
        'tax',
        'total',
        'balance_due',
        'external_reference',
        'metadata',
    ];

    protected $casts = [
        'status'        => InvoiceStatus::class,
        'invoice_date'  => 'date',
        'due_date'      => 'date',
        'subtotal'      => 'decimal:2',
        'tax'           => 'decimal:2',
        'total'         => 'decimal:2',
        'balance_due'   => 'decimal:2',
        'metadata'      => 'array',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function cemetery(): BelongsTo
    {
        return $this->belongsTo(InternalCemetery::class, 'internal_cemetery_id');
    }

    // public function monumentRetailer(): BelongsTo
    // {
    //     return $this->belongsTo(InternalMonumentRetailer::class, 'internal_monument_retailer_id');
    // }

    public function family(): BelongsTo
    {
        return $this->belongsTo(Family::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function lineItems(): HasMany
    {
        return $this->hasMany(InvoiceLineItem::class);
    }

    public function payments()
    {
        return $this->belongsToMany(Payment::class, 'payment_invoice')
            ->withPivot('amount_applied')
            ->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | Business Logic
    |--------------------------------------------------------------------------
    */
    public function applyPayment(Payment $payment, float $amount): void
    {
        if ($amount <= 0) {
            throw new \DomainException("Amount must be greater than zero.");
        }

        if ($amount > $payment->unapplied_amount) {
            throw new \DomainException("Not enough unapplied funds on this payment.");
        }

        $payment->applyToInvoice($this, $amount);
    }


    public function recalcTotals(): void
    {
        $subtotal = $this->lineItems->sum('amount');
        $this->subtotal = $subtotal;
        $this->tax = 0;
        $this->total = $subtotal + $this->tax;
        $this->balance_due = $this->total - $this->payments->sum('pivot.amount_applied');
        $this->save();
    }

    public function issuer(): string
    {
        return $this->internal_cemetery_id
            ? $this->cemetery?->name ?? 'Unknown Cemetery'
            : $this->monumentRetailer?->name ?? 'Unknown Retailer';
    }
}
