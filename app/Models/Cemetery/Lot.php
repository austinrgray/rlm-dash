<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Lot extends Model
{
    /** @use HasFactory<\Database\Factories\LotFactory> */
    use HasFactory;

    protected $fillable = [
        'section_id',
        'lot_number',
        'lot_letter',
        'max_capacity',
        'available_plot_count',
        'grid_reference',
        'notes',
    ];

    protected $casts = [
        'max_capacity' => 'integer',
        'available_plot_count' => 'integer',
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function plots(): HasMany
    {
        return $this->hasMany(Plot::class);
    }

    public function intermentRecords(): HasManyThrough
    {
        return $this->hasManyThrough(IntermentRecord::class, Plot::class);
    }
}
