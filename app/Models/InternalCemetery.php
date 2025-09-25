<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class InternalCemetery extends Model
{
    protected $fillable = [
        'name',
    ];

    public function contactCards(): MorphMany
    {
        return $this->morphMany(ContactCard::class, 'contactable');
    }

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class);
    }

    public function plots(): HasMany
    {
        return $this->hasMany(Plot::class);
    }

    public function intermentRecords(): HasMany
    {
        return $this->hasMany(IntermentRecord::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(IntermentRecord::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(IntermentRecord::class);
    }

    public function reciepts(): HasMany
    {
        return $this->hasMany(IntermentRecord::class);
    }
}
