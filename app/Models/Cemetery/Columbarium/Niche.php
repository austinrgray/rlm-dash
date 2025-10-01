<?php

namespace App\Models\Cemetery\Columbarium;

use App\Models\Cemetery\InternalCemetery;
use App\Models\Interment\Interment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Niche extends Model
{
    use HasFactory;

    protected $fillable = [
        'internal_cemetery_id',
        'columbarium_id',
        'columbarium_name',
        'niche_number',
        'grid_reference',
        'capacity',
        'notes',
    ];

    protected $casts = [
        'capacity'    => 'integer',
        'niche_number' => 'integer',
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

    public function columbarium(): BelongsTo
    {
        return $this->belongsTo(Columbarium::class);
    }

    public function interments()
    {
        return $this->morphMany(Interment::class, 'intermentable');
    }
}
