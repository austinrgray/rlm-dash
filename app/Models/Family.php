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

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function members(): HasManyThrough
    {
        return $this->hasManyThrough(
            Person::class,
            FamilyMember::class,
            'family_id',
            'id',
            'id',
            'person_id'
        );
    }

    public function familyMembers(): HasMany
    {
        return $this->hasMany(FamilyMember::class);
    }

    public function organizations(): HasMany
    {
        return $this->hasMany(Organization::class);
    }

    public function contactCards(): MorphMany
    {
        return $this->morphMany(ContactCard::class, 'contactable');
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
            'id',
            'person_id',
            'id',
            'id'
        );
    }

    public function burialRights(): HasMany
    {
        return $this->hasMany(BurialRight::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */
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

    public function getPrimaryContactCardAttribute(): ?ContactCard
    {
        return $this->contactCards()->first();
    }

    public function getHeadOfHouseholdAttribute(): ?Person
    {
        return $this->familyMembers()
            ->where('role', 'head')
            ->first()
            ?->person;
    }

    public function getOrganizationContactsAttribute(): string
    {
        if ($this->organizations->isEmpty()) {
            return '—';
        }

        return $this->organizations
            ->flatMap(fn($org) => $org->contactCards->map(function ($card) {
                $lines = [];
                if ($card->phone) {
                    $lines[] = "📞 {$card->phone}";
                }
                if ($card->email) {
                    $lines[] = "✉️ {$card->email}";
                }
                return implode('<br>', $lines);
            }))
            ->implode('<br>──────────────<br>');
    }

    public function getFamilyContactsAttribute(): string
    {
        if ($this->contactCards->isEmpty()) {
            return '<span class="text-gray-400">—</span>';
        }

        return $this->contactCards
            ->map(function ($card) {
                $lines = [];
                if ($card->phone) {
                    $lines[] = "📞 {$card->phone}";
                }
                if ($card->email) {
                    $lines[] = "✉️ {$card->email}";
                }
                return implode('<br>', $lines);
            })
            ->implode('<br>──────────────<br>');
    }

    public function getMembersWithRolesAttribute(): string
    {
        if ($this->familyMembers->isEmpty()) {
            return '<span class="text-gray-400">—</span>';
        }

        return $this->familyMembers
            ->map(
                fn($fm) => $fm->person
                    ? "{$fm->person->first_name} {$fm->person->last_name} ({$fm->role})"
                    : '[Unknown Person]'
            )
            ->join(', ');
    }

    public function getIntermentsSummaryAttribute(): string
    {
        if ($this->intermentRecords->isEmpty()) {
            return '<span class="text-gray-400">—</span>';
        }

        return $this->intermentRecords
            ->map(function ($record) {
                $date = $record->date_of_interment
                    ? $record->date_of_interment->format('M d, Y')
                    : '[No interment date]';

                $plot = $record->plot;
                if ($plot) {
                    $section = $plot->section?->name ?? 'Unknown Section';
                    $lotNum = $plot->lot?->lot_number ?? '?';
                    $lotLetter = $plot->lot?->lot_letter ?? '';
                    $plotNum = $plot->plot_number ?? '?';

                    $location = "{$section} - {$lotNum}{$lotLetter} - Plot {$plotNum}";
                } else {
                    $location = '[No plot]';
                }

                return "{$date}<br>{$location}";
            })
            ->implode('<br>──────────────<br>');
    }

    public function getOwnedPlotsSummaryAttribute(): string
    {
        if ($this->burialRights->isEmpty()) {
            return '<span class="text-gray-400">—</span>';
        }

        return $this->burialRights
            ->map(function ($right) {
                $plot = $right->plot;
                if ($plot) {
                    $section = $plot->section?->name ?? 'Unknown Section';
                    $lotNum = $plot->lot?->lot_number ?? '?';
                    $lotLetter = $plot->lot?->lot_letter ?? '';
                    $plotNum = $plot->plot_number ?? '?';

                    return "{$section} - {$lotNum}{$lotLetter} - Plot {$plotNum}";
                }

                return '[No plot]';
            })
            ->implode('<br>──────────────<br>');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */
    public function livingMembers()
    {
        return $this->members()->whereDoesntHave('intermentRecord');
    }

    public function deceasedMembers()
    {
        return $this->members()->whereHas('intermentRecord');
    }
}
