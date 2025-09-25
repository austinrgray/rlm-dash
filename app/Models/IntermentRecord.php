<?php

namespace App\Models;

use App\Models\Order;
use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IntermentRecord extends Model
{
    /** @use HasFactory<\Database\Factories\IntermentRecordFactory> */
    use HasFactory;

    protected $fillable = [
        'person_id',
        'date_of_death',
        'date_of_interment',
        'internal_cemetery_id',
        'external_cemetery_id',
        'plot_id',
        'disposition_type',
        'vault_type',
        'funeral_home',
        'order_id',
    ];

    protected $casts = [
        'date_of_death' => 'date',
        'date_of_interment' => 'date',
    ];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function internalCemetery(): BelongsTo
    {
        return $this->belongsTo(InternalCemetery::class);
    }

    public function externalCemetery(): BelongsTo
    {
        return $this->belongsTo(ExternalCemetery::class);
    }

    public function plot(): BelongsTo
    {
        return $this->belongsTo(Plot::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
