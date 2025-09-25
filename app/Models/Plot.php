<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Plot extends Model
{
    /** @use HasFactory<\Database\Factories\PlotFactory> */
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
        'traditional_burials' => 'integer',
        'cremation_burials' => 'integer',
        'max_traditional_burials' => 'integer',
        'max_cremation_burials' => 'integer',
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function lot(): BelongsTo
    {
        return $this->belongsTo(Lot::class);
    }

    public function intermentRecords(): HasMany
    {
        return $this->hasMany(IntermentRecord::class);
    }

    public function burialRight(): HasOne
    {
        return $this->hasOne(BurialRight::class);
    }

    public function getIsOwnedAttribute(): bool
    {
        return $this->burialRight() !== null;
    }

    public function getCurrentOwnerAttribute()
    {
        return $this->burialRight?->purchaser;
    }
}
