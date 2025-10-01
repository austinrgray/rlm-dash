<?php

namespace App\Models\Finance;

use App\Enums\Finance\PaymentMethodType;
use App\Enums\Finance\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'payment_method',
        'transaction_ref',
        'date',
        'status',
        'metadata',
    ];

    protected $casts = [
        'amount'         => 'decimal:2',
        'date'           => 'date',
        'payment_method' => PaymentMethodType::class,
        'status'         => PaymentStatus::class,
        'metadata'       => 'array',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function invoices(): BelongsToMany
    {
        return $this->belongsToMany(Invoice::class, 'invoice_payment')
            ->withPivot('amount_applied')
            ->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */
    public function getAppliedAmountAttribute(): float
    {
        return $this->invoices->sum('pivot.amount_applied');
    }

    public function getUnappliedAmountAttribute(): float
    {
        return round($this->amount - $this->applied_amount, 2);
    }

    public function getIsFullyAppliedAttribute(): bool
    {
        return $this->unapplied_amount <= 0.01;
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */
    public function applyToInvoice(Invoice $invoice, float $amount): void
    {
        if ($amount <= 0) {
            throw new \DomainException("Amount must be greater than zero.");
        }

        if ($amount > $this->unapplied_amount) {
            throw new \DomainException("Not enough unapplied funds to apply this payment.");
        }

        $existing = $this->invoices()->where('invoice_id', $invoice->id)->first();

        if ($existing) {
            $newAmount = $existing->pivot->amount_applied + $amount;
            $this->invoices()->updateExistingPivot($invoice->id, ['amount_applied' => $newAmount]);
        } else {
            $this->invoices()->attach($invoice->id, ['amount_applied' => $amount]);
        }

        $invoice->recalcTotals();
        $this->refresh();
    }
}
