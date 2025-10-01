<?php

namespace App\Models\Operation;

use App\Models\Finance\Invoice;
use App\Models\Finance\InvoiceLineItem;
use App\Models\Operation\OrderTask;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'title',
        'status',
        'scheduled_date',
        'metadata',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'metadata'       => 'array',
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

    public function lineItems(): BelongsToMany
    {
        return $this->belongsToMany(InvoiceLineItem::class, 'order_line_item')
            ->withTimestamps();
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(OrderTask::class);
    }
}
