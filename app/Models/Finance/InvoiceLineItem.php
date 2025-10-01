<?php

namespace App\Models\Finance;

use App\Enums\Finance\LineItemCategory;
use App\Enums\Finance\LineItemCode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceLineItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'line_item_type',
        'line_item_code',
        'invoiceable_type',
        'invoiceable_id',
        'description',
        'quantity',
        'unit_price',
        'amount',
    ];

    protected $casts = [
        'line_item_type' => LineItemCategory::class,
        'line_item_code' => LineItemCode::class,
        'unit_price'     => 'decimal:2',
        'amount'         => 'decimal:2',
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
    | Logic
    |--------------------------------------------------------------------------
    */
    protected static function booted()
    {
        static::saving(function (InvoiceLineItem $item) {
            if ($item->line_item_code && $item->line_item_type) {
                if (! $item->line_item_code->isValidCategory($item->line_item_type)) {
                    throw new \DomainException(
                        "Line item code {$item->line_item_code->value} does not match category {$item->line_item_type->value}."
                    );
                }
            }

            $item->amount = $item->quantity * $item->unit_price;
        });
    }
}
