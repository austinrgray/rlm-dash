<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Family extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_active',
        'notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getNameAttribute(): string
    {
        $head = $this->headOfHousehold;

        if ($head) {
            return "{$head->last_name} Family";
        }

        $anyMember = $this->members()->first();
        if ($anyMember) {
            return "{$anyMember->last_name} Family";
        }

        return "Orphaned Family #{$this->id}";
    }

    public function members(): HasManyThrough
    {
        return $this->hasManyThrough(
            Person::class,
            FamilyMember::class,
            'family_id', // Foreign key on FamilyMember table
            'id', // Foreign key on Person table
            'id', // Local key on Family table
            'person_id' // Local key on FamilyMember table
        );
    }

    public function organizations(): HasMany
    {
        return $this->hasMany(Organization::class);
    }


    public function familyMembers(): HasMany
    {
        return $this->hasMany(FamilyMember::class);
    }

    public function getPrimaryContactCardAttribute()
    {
        return $this->contactCards()->first();
    }

    public function contactCards(): MorphMany
    {
        return $this->morphMany(ContactCard::class, 'contactable');
    }

    public function getHeadOfFamilyAttribute(): ?Person
    {
        return $this->familyMembers()
            ->where('role', 'head')
            ->first()
            ?->person;
    }

    public function burialRightBundles(): HasMany
    {
        return $this->hasMany(BurialRightBundle::class);
    }

    public function burialRights(): HasManyThrough
    {
        return $this->hasManyThrough(
            BurialRight::class,
            BurialRightBundle::class,
            'family_id', // Foreign key on BurialRightBundle table
            'burial_right_bundle_id', // Foreign key on BurialRight table
            'id', // Local key on Family table
            'id' // Local key on BurialRightBundle table
        );
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function intermentRecords(): HasManyThrough
    {
        return $this->hasManyThrough(
            IntermentRecord::class,
            Person::class,
            'id', // Foreign key on Person table
            'person_id', // Foreign key on IntermentRecord table
            'id', // Local key on Family table
            'id' // Local key on Person table
        );
    }

    public function livingMembers()
    {
        return $this->members()->whereDoesntHave('intermentRecord');
    }

    public function deceasedMembers()
    {
        return $this->members()->whereHas('intermentRecord');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getOrganizationContactsAttribute(): string
    {
        if ($this->organizations->isEmpty()) {
            return '—';
        }

        return $this->organizations
            ->flatMap(function ($org) {
                return $org->contactCards->map(function ($card) use ($org) {
                    $lines = [];
                    if ($card->phone) {
                        $lines[] = "📞 {$card->phone}";
                    }
                    if ($card->email) {
                        $lines[] = "✉️ {$card->email}";
                    }
                    return implode('<br>', $lines);
                });
            })
            ->implode('<br>──────────────<br>');
    }
}
