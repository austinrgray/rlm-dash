<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ExternalCemetery extends Model
{
    /** @use HasFactory<\Database\Factories\ExternalCemeteryFactory> */
    use HasFactory;
    protected $fillable = [
        'name',
        'is_active',
        'notes',
    ];

    public function contactCards(): MorphMany
    {
        return $this->morphMany(ContactCard::class, 'contactable');
    }

    public function getPrimaryContactCardAttribute()
    {
        return $this->contactCards()->first();
    }

    public function intermentRecords(): HasMany
    {
        return $this->hasMany(IntermentRecord::class);
    }
}
