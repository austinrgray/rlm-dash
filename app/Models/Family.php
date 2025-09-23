<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Family extends Model
{
    use HasFactory;

    protected $fillable = [
        'notes',
    ];

    /**
     * Dynamic accessor for the family name.
     * Returns "The [Lastname] Family".
     */
    public function getNameAttribute(): string
    {
        try {
            // Eager load the head member and their person relationship to prevent N+1 queries
            $headMember = $this->members->where('role', 'head')->first();

            // Check if we found a head member AND that member has a person relation loaded
            if ($headMember && $headMember->relationLoaded('person') && $headMember->person) {
                return "The {$headMember->person->last_name} Family";
            }

            // If no head member found, try to get any member's last name as fallback
            $anyMember = $this->members->first();
            if ($anyMember && $anyMember->relationLoaded('person') && $anyMember->person) {
                return "The {$anyMember->person->last_name} Family";
            }
        } catch (\Exception $e) {
            // Log the error for debugging, but don't break the application
            //Log::warning("Error getting family name for family #{$this->id}: " . $e->getMessage());
        }

        // Ultimate fallback
        return "Family #{$this->id}";
    }

    // Relationships (to be implemented later)
    public function members(): HasMany
    {
        // We will create a FamilyMember model later
        return $this->hasMany(FamilyMember::class);
    }

    public function getHeadOfFamilyAttribute(): ?Person
    {
        $headMember = $this->members()->where('role', 'head')->first();
        return $headMember ? $headMember->person : null;
    }

    // public function contactCards(): HasMany
    // {
    //     // We will create a ContactCard model later
    //     return $this->hasMany(ContactCard::class);
    // }

}
