<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Organization extends Model
{
    /** @use HasFactory<\Database\Factories\OrganizationFactory> */
    use HasFactory;
    protected $fillable = [
        'name',
        'type',
        'is_active',
        'notes',
    ];

    public function contactCards(): MorphMany
    {
        return $this->morphMany(ContactCard::class, 'contactable');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
