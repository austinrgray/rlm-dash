<?php

namespace App\Models\Cemetery\Mausoleum;

use App\Models\Cemetery\InternalCemetery;
use App\Models\Interment\Interment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Crypt extends Model
{
    use HasFactory;

    protected $fillable = [
        'internal_cemetery_id',
        'mausoleum_id',
        'mausoleum_name',
        'crypt_number',
        'grid_reference',
        'capacity',
        'notes',
    ];

    protected $casts = [
        'capacity'    => 'integer',
        'crypt_number' => 'integer',
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

    public function mausoleum(): BelongsTo
    {
        return $this->belongsTo(Mausoleum::class);
    }

    public function interments()
    {
        return $this->morphMany(Interment::class, 'intermentable');
    }
}
