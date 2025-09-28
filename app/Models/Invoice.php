<?php

namespace App\Models;

use App\Enums\InvoiceStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'family_id',
        'organization_id',
        'status',
        'due_date',
    ];

    protected $casts = [
        'status'      => InvoiceStatus::class,
        'due_date'    => 'date',
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

    public function lineItems(): HasMany
    {
        return $this->hasMany(InvoiceLineItem::class);
    }

    public function payments(): BelongsToMany
    {
        return $this->belongsToMany(Payment::class)
            ->withPivot('amount_applied')
            ->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */
    public function getDisplayLabelAttribute(): string
    {
        $payer = $this->family?->name ?? $this->organization?->name ?? 'Unknown';
        return "Invoice #{$this->id} — {$payer}";
    }

    public function getIsPaidAttribute(): bool
    {
        return $this->status === InvoiceStatus::Paid || $this->balance_due <= 0;
    }

    public function getPurchaserAttribute(): Family|Organization|null
    {
        return $this->family ?? $this->organization;
    }

    /*
    |--------------------------------------------------------------------------
    | Business Logic
    |--------------------------------------------------------------------------
    */
    public function totalAmount(): float
    {
        return $this->lineItems->sum('amount');
    }

    public function totalPaid(): float
    {
        return $this->payments->sum('pivot.amount_applied');
    }

    public function calculateBalanceDue(): float
    {
        return $this->totalAmount() - $this->totalPaid();
    }

    public function recalcBalance(): void
    {
        $this->balance_due = $this->calculateBalanceDue();
        $this->save();
    }

    public function applyPayment(Payment $payment, float $amount): void
    {
        $this->validatePayment($amount, $payment);

        $this->payments()->attach($payment->id, [
            'amount_applied' => $amount,
        ]);

        $this->updateBalanceAndStatus();
    }

    protected function updateBalanceAndStatus(): void
    {
        $this->balance_due = $this->calculateBalanceDue();

        if ($this->balance_due <= 0) {
            $this->status = InvoiceStatus::Paid;
        } elseif ($this->totalPaid() > 0) {
            $this->status = InvoiceStatus::Partial;
        } else {
            $this->status = InvoiceStatus::Open;
        }

        $this->save();
    }

    protected function validatePayment(float $amount, Payment $payment): void
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Payment amount must be positive.');
        }

        if ($amount > $this->balance_due) {
            throw new \DomainException("Payment exceeds outstanding balance.");
        }

        if ($amount > $payment->unappliedAmount()) {
            throw new \DomainException("Payment does not have enough unapplied funds.");
        }
    }
}
