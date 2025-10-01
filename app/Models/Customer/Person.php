<?php

namespace App\Models\Customer;

use App\Enums\Customer\Gender;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

class Person extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'date_of_birth',
        'date_of_death',
        'gender',
        'notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'date_of_death' => 'date',
        'gender' => Gender::class,
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function families(): BelongsToMany
    {
        return $this->belongsToMany(Family::class, 'family_members')
            ->using(FamilyMember::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    // public function intermentRecord(): HasOne
    // {
    //     return $this->hasOne(IntermentRecord::class);
    // }

}
