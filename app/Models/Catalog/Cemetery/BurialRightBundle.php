<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class BurialRightBundle extends Model
{
    /** @use HasFactory<\Database\Factories\BurialRightBundleFactory> */
    use HasFactory;
    protected $fillable = [
        'family_id',
        'organization_id',
        'order_id',
        'transaction_date',
        'notes',
    ];

    protected $casts = [
        'transaction_date' => 'date',
    ];

    public function family(): BelongsTo
    {
        return $this->belongsTo(Family::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function purchaser()
    {
        return $this->family ?? $this->organization;
    }

    public function burialRights(): HasMany
    {
        return $this->hasMany(BurialRight::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function plots(): HasManyThrough
    {
        return $this->hasManyThrough(Plot::class, BurialRight::class);
    }
}
