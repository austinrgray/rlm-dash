<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class InternalCemetery extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
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

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class);
    }

    public function lots(): HasManyThrough
    {
        return $this->hasManyThrough(Lot::class, Section::class);
    }

    public function plots(): HasManyThrough
    {
        return $this->hasManyThrough(Plot::class, Lot::class);
    }

    public function intermentRecords(): HasMany
    {
        return $this->hasMany(IntermentRecord::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
