<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'internal_cemetery_id',
        'name',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function cemetery(): BelongsTo
    {
        return $this->belongsTo(InternalCemetery::class, 'internal_cemetery_id');
    }

    public function lots(): HasMany
    {
        return $this->hasMany(Lot::class);
    }

    public function plots(): HasManyThrough
    {
        return $this->hasManyThrough(Plot::class, Lot::class);
    }

    public function intermentRecords(): HasManyThrough
    {
        return $this->hasManyThrough(IntermentRecord::class, Plot::class);
    }
}
