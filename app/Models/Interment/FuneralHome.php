<?php

namespace App\Models\Interment;

use App\Models\Shared\ContactCard;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class FuneralHome extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function contactCards(): MorphMany
    {
        return $this->morphMany(ContactCard::class, 'contactable');
    }

    public function interments(): HasMany
    {
        return $this->hasMany(Interment::class);
    }
}
