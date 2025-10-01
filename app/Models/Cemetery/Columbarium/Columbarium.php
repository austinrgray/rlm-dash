<?php

namespace App\Models\Cemetery\Columbarium;

use App\Models\Cemetery\InternalCemetery;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Columbarium extends Model
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

    public function niches(): HasMany
    {
        return $this->hasMany(Niche::class);
    }
}
