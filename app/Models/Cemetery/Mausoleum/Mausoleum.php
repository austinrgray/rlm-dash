<?php

namespace App\Models\Cemetery\Mausoleum;

use App\Models\Cemetery\InternalCemetery;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mausoleum extends Model
{
    use HasFactory;

    protected $fillable = [
        'internal_cemetery_id',
        'name',
        'grid_reference',
        'notes',
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

    public function crypts(): HasMany
    {
        return $this->hasMany(Crypt::class);
    }
}
