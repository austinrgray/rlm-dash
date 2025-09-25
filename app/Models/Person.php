<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

class Person extends Model
{
    use HasFactory;
    protected $fillable = [
        'last_name',
        'middle_name',
        'first_name',
        'date_of_birth',
        'gender',
        'notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function families(): BelongsToMany
    {
        return $this->belongsToMany(Family::class, 'family_members')
            ->using(FamilyMember::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    public function intermentRecord(): HasOne
    {
        return $this->hasone(IntermentRecord::class);
    }

    public function getIsDeceasedAttribute(): bool
    {
        return $this->intermentRecord()->exists();
    }

    public function getDateOfDeathAttribute(): ?Carbon
    {
        return $this->intermentRecord?->date_of_death;
    }
}
