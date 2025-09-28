<?php

namespace App\Models;

use App\Enums\FamilyRole;
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

    public function intermentRecords()
    {
        return IntermentRecord::whereIn(
            'person_id',
            $this->familyMembers()->pluck('person_id')
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
            ->where('role', FamilyRole::HeadOfFamily->value)->first()?->person;
    }

    public function getOrganizationContactSummariesAttribute(): array
    {
        return $this->organizations->map(fn($card) => [
            'label' => $card->label,
            'phone' => $card->phone ?? null,
            'email' => $card->email ?? null,
        ])->toArray();
    }

    public function getContactSummariesAttribute(): array
    {
        return $this->contactCards->map(fn($card) => [
            'label' => $card->label,
            'phone' => $card->phone ?? null,
            'email' => $card->email ?? null,
        ])->toArray();
    }

    public function getMembersWithRolesAttribute(): string
    {
        return $this->familyMembers->map(fn($fm) => [
            'first_name' => $fm->person?->first_name,
            'last_name'  => $fm->person?->last_name,
            'role'       => $fm->role,
        ])->toArray();
    }

    public function getIntermentsSummaryAttribute(): string
    {
        return $this->intermentRecords->map(function ($record) {
            return [
                'date' => $record->date_of_interment?->format('Y-m-d'),
                'section' => $record->plot?->section?->name ?? null,
                'lot_number' => $record->plot?->lot?->lot_number,
                'lot_letter' => $record->plot?->lot?->lot_letter,
                'plot_number' => $record->plot?->plot_number,
            ];
        })->toArray();
    }

    public function getBurialRightsSummaryAttribute(): string
    {
        return $this->burialRights->map(function ($right) {
            return [
                'section' => $right->plot?->section?->name ?? null,
                'lot_number' => $right->plot?->lot?->lot_number,
                'lot_letter' => $right->plot?->lot?->lot_letter,
                'plot_number' => $right->plot?->plot_number,
            ];
        })->toArray();
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
