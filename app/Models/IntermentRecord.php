<?php

namespace App\Models;

use App\Enums\DispositionType;
use App\Enums\IntermentStatus;
use App\Enums\VaultType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IntermentRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'person_id',
        'external_cemetery_id',
        'plot_id',
        'date_of_death',
        'date_of_interment',
        'disposition_type',
        'vault_type',
        'funeral_home',
        'status',
    ];

    protected $casts = [
        'date_of_death' => 'date',
        'date_of_interment' => 'date',
        'disposition_type' => DispositionType::class,
        'vault_type' => VaultType::class,
        'status' => IntermentStatus::class,
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function externalCemetery(): BelongsTo
    {
        return $this->belongsTo(ExternalCemetery::class);
    }

    public function plot(): BelongsTo
    {
        return $this->belongsTo(Plot::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Derived Relationships
    |--------------------------------------------------------------------------
    */
    public function internalCemetery(): ?InternalCemetery
    {
        return $this->plot?->lot?->section?->cemetery;
    }
}
