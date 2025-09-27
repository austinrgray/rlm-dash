<?php

namespace App\Models;

use App\Enums\BurialRightStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BurialRight extends Model
{
    use HasFactory;

    protected $fillable = [
        'family_id',
        'organization_id',
        'invoice_id',
        'plot_id',
        'status',
        'notes',
    ];

    protected $casts = [
        'status' => BurialRightStatus::class,
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

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function plot(): BelongsTo
    {
        return $this->belongsTo(Plot::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */
    public function getOwnerAttribute(): Family|Organization
    {
        return $this->family ?? $this->organization;
    }
}
