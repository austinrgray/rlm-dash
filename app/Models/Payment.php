<?php

namespace App\Models;

use App\Enums\PaymentMethodType;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'payment_method',
        'transaction_reference',
        'date',
        'status',
    ];

    protected $casts = [
        'amount'          => 'decimal:2',
        'date'            => 'date',
        'payment_method'  => PaymentMethodType::class,
        'status'          => PaymentStatus::class,
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function invoices(): BelongsToMany
    {
        return $this->belongsToMany(Invoice::class)
            ->withPivot('amount_applied')
            ->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */
    public function appliedAmount(): float
    {
        return $this->invoices->sum('pivot.amount_applied');
    }

    public function unappliedAmount(): float
    {
        return $this->amount - $this->appliedAmount();
    }

    public function isFullyApplied(): bool
    {
        return $this->unappliedAmount() <= 0.01;
    }
}
