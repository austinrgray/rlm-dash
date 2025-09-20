<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Family extends Model
{
    use HasFactory;

    protected $fillable = [
        'internal_notes',
    ];

    /**
     * Dynamic accessor for the family name.
     * Returns "The [Lastname] Family".
     */
    public function getNameAttribute(): string
    {
        // Find the primary member for this family
        $primaryMember = $this->members()->where('is_primary', true)->first();

        if ($primaryMember) {
            return "The {$primaryMember->person->last_name} Family";
        }

        return "Family #{$this->id}";
    }

    // Relationships (to be implemented later)
    public function members(): HasMany
    {
        // We will create a FamilyMember model later
        return $this->hasMany(FamilyMember::class);
    }

    public function contactCards(): HasMany
    {
        // We will create a ContactCard model later
        return $this->hasMany(ContactCard::class);
    }
}
