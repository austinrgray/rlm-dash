<?php

namespace App\Models\Cemetery;

use App\Enums\Interment\DispositionType;
use App\Models\Customer\Family;
use App\Models\Customer\Organization;
use App\Models\Interment\Interment;
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
        'internal_cemetery_id',
        'section_id',
        'lot_id',
        'plot_number',
        'grid_reference',
        'traditional_capacity',
        'cremation_capacity',
        'notes',
    ];

    protected $casts = [
        'traditional_capacity'      => 'integer',
        'cremation_capacity'        => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function cemetery(): BelongsTo
    {
        return $this->belongsTo(InternalCemetery::class);
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function lot(): BelongsTo
    {
        return $this->belongsTo(Lot::class);
    }

    public function interments()
    {
        return $this->morphMany(Interment::class, 'intermentable');
    }

    // public function burialRight(): HasOne
    // {
    //     return $this->hasOne(BurialRight::class);
    // }

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
        return max(0, $this->traditional_capacity - $this->traditional_burials);
    }

    public function getAvailableCremationSlotsAttribute(): int
    {
        return max(0, $this->cremation_capacity - $this->cremation_burials);
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
                default => throw new \DomainException("Invalid disposition type for plots."),
            };

            return ['success' => true, 'message' => "Interment applied successfully."];
        } catch (\Throwable $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function removeInterment(DispositionType $type, int $count = 1): array
    {
        try {
            match ($type) {
                DispositionType::TraditionalBurial => $this->decrementTraditional($count),
                DispositionType::CremationBurial   => $this->decrementCremation($count),
                default => throw new \DomainException("Invalid disposition type for plots."),
            };

            return ['success' => true, 'message' => "Interment removed successfully."];
        } catch (\Throwable $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
