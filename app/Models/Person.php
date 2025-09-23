<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Person extends Model
{
    use HasFactory;
    protected $fillable = [
        'last_name',
        'middle_name',
        'first_name',
        'date_of_birth',
        'date_of_death',
        'notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'date_of_death' => 'date',
    ];

    public function families(): BelongsToMany
    {
        return $this->belongsToMany(Family::class, 'family_members')
            ->using(FamilyMember::class)
            ->withPivot('role', 'is_primary')
            ->withTimestamps();
    }
}
