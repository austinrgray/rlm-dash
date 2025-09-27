<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Plot extends Model
{
    use HasFactory;

    protected $fillable = [
        'lot_id',
        'plot_number',
        'grid_reference',
        'traditional_burials',
        'cremation_burials',
        'max_traditional_burials',
        'max_cremation_burials',
        'notes',
    ];

    protected $casts = [
        'traditional_burials'     => 'integer',
        'cremation_burials'       => 'integer',
        'max_traditional_burials' => 'integer',
        'max_cremation_burials'   => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function lot(): BelongsTo
    {
        return $this->belongsTo(Lot::class);
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function cemetery(): BelongsTo
    {
        return $this->belongsTo(InternalCemetery::class);
    }

    public function intermentRecords(): HasMany
    {
        return $this->hasMany(IntermentRecord::class);
    }

    public function burialRight(): HasOne
    {
        return $this->hasOne(BurialRight::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */
    public function getIsOwnedAttribute(): bool
    {
        return $this->relationLoaded('burialRight')
            ? $this->burialRight !== null
            : $this->burialRight()->exists();
    }

    public function getCurrentOwnerAttribute(): Family|Organization|null
    {
        return $this->burialRight?->owner;
    }

    public function getAvailableTraditionalSlotsAttribute(): int
    {
        return max(0, $this->max_traditional_burials - $this->traditional_burials);
    }

    public function getAvailableCremationSlotsAttribute(): int
    {
        return max(0, $this->max_cremation_burials - $this->cremation_burials);
    }

    /*
    |--------------------------------------------------------------------------
    | Mutators (Counters Only)
    |--------------------------------------------------------------------------
    */
    public function incrementTraditional(int $count = 1): void
    {
        $this->increment('traditional_burials', $count);
    }

    public function decrementTraditional(int $count = 1): void
    {
        $this->decrement('traditional_burials', $count);
    }

    public function incrementCremation(int $count = 1): void
    {
        $this->increment('cremation_burials', $count);
    }

    public function decrementCremation(int $count = 1): void
    {
        $this->decrement('cremation_burials', $count);
    }
}
