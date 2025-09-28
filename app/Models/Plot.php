<?php

namespace App\Models;

use App\Enums\DispositionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
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
        'mausoleum_entombments',
        'columbarium_entombments',
        'max_traditional_burials',
        'max_cremation_burials',
        'max_mausoleum_entombments',
        'max_columbarium_entombments',
        'notes',
    ];

    protected $casts = [
        'traditional_burials'     => 'integer',
        'cremation_burials'       => 'integer',
        'mausoleum_entombments'       => 'integer',
        'columbarium_entombments'     => 'integer',
        'max_traditional_burials' => 'integer',
        'max_cremation_burials'   => 'integer',
        'max_mausoleum_entombments'   => 'integer',
        'max_columbarium_entombments' => 'integer',
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

    public function section(): HasOneThrough
    {
        return $this->hasOneThrough(Section::class, Lot::class);
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
    | Derived Relationships
    |--------------------------------------------------------------------------
    */
    public function cemetery(): ?InternalCemetery
    {
        return $this->lot?->section?->cemetery;
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
    public function applyInterment(DispositionType $type, int $count = 1): array
    {
        try {
            match ($type) {
                DispositionType::TraditionalBurial => $this->incrementTraditional($count),
                DispositionType::CremationBurial   => $this->incrementCremation($count),
                DispositionType::MausoleumEntombment => $this->incrementMausoleum($count),
                DispositionType::ColumbariumEntombment   => $this->incrementColumbarium($count),
                default => throw new \DomainException("Invalid disposition type for this operation."),
            };

            return [
                'success' => true,
                'message' => "Interment applied successfully.",
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function removeInterment(DispositionType $type, int $count = 1): array
    {
        try {
            match ($type) {
                DispositionType::TraditionalBurial => $this->decrementTraditional($count),
                DispositionType::CremationBurial   => $this->decrementCremation($count),
                DispositionType::MausoleumEntombment => $this->decrementMausoleum($count),
                DispositionType::ColumbariumEntombment   => $this->decrementColumbarium($count),
                default => throw new \DomainException("Invalid disposition type for this operation."),
            };

            return [
                'success' => true,
                'message' => "Interment removed successfully.",
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function incrementTraditional(int $count = 1): void
    {
        if ($this->traditional_burials + $count > $this->max_traditional_burials) {
            throw new \DomainException("Exceeds maximum traditional burials for this plot.");
        }

        $this->increment('traditional_burials', $count);
    }

    public function decrementTraditional(int $count = 1): void
    {
        if ($this->traditional_burials - $count < 0) {
            throw new \DomainException("Cannot have fewer than 0 traditional burials.");
        }

        $this->decrement('traditional_burials', $count);
    }

    public function incrementCremation(int $count = 1): void
    {
        if ($this->cremation_burials + $count > $this->max_cremation_burials) {
            throw new \DomainException("Exceeds maximum cremation burials for this plot.");
        }

        $this->increment('cremation_burials', $count);
    }

    public function decrementCremation(int $count = 1): void
    {
        if ($this->cremation_burials - $count < 0) {
            throw new \DomainException("Cannot have fewer than 0 cremation burials.");
        }

        $this->decrement('cremation_burials', $count);
    }

    public function incrementMausoleum(int $count = 1): void
    {
        if ($this->mausoleum_entombments + $count > $this->max_mausoleum_entombments) {
            throw new \DomainException("Exceeds maximum mausoleum entombments for this plot.");
        }

        $this->increment('mausoleum_entombments', $count);
    }

    public function decrementMausoleum(int $count = 1): void
    {
        if ($this->mausoleum_entombments - $count < 0) {
            throw new \DomainException("Cannot have fewer than 0 mausoleum entombments.");
        }

        $this->decrement('mausoleum_entombments', $count);
    }

    public function incrementColumbarium(int $count = 1): void
    {
        if ($this->columbarium_entombments + $count > $this->max_columbarium_entombments) {
            throw new \DomainException("Exceeds maximum columbarium entombments for this plot.");
        }

        $this->increment('columbarium_entombments', $count);
    }

    public function decrementColumbarium(int $count = 1): void
    {
        if ($this->columbarium_entombments - $count < 0) {
            throw new \DomainException("Cannot have fewer than 0 columbarium entombments.");
        }

        $this->decrement('columbarium_entombments', $count);
    }
}
