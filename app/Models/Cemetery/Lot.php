<?php

namespace App\Models\Cemetery;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Lot extends Model
{
    use HasFactory;

    protected $fillable = [
        'internal_cemetery_id',
        'section_id',
        'lot_number',
        'lot_letter',
        'grid_reference',
        'notes',
    ];

    protected $casts = [
        'max_capacity' => 'integer',
        'available_plot_count' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function cemetery()
    {
        return $this->belongsTo(InternalCemetery::class, 'internal_cemetery_id');
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function plots(): HasMany
    {
        return $this->hasMany(Plot::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */
    public function getLotLabelAttribute(): string
    {
        return trim($this->lot_number . $this->lot_letter);
    }
}
