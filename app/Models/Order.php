<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'family_id',
        'organization_id',
        'order_type',
        'status',
        'scheduled_for',
        'completed_at',
        'notes',
    ];

    protected $casts = [
        'order_type'    => OrderType::class,
        'status'        => OrderStatus::class,
        'scheduled_for' => 'datetime',
        'completed_at'  => 'datetime',
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

    public function orderable(): MorphTo
    {
        return $this->morphTo();
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    // public function invoiceLineItem(): MorphOne
    // {
    //     return $this->morphOne(InvoiceLineItem::class, 'invoiceable');
    // }

    // public function tasks(): HasMany
    // {
    //     return $this->hasMany(Task::class);
    // }
}
