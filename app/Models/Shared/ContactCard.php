<?php

namespace App\Models\Shared;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ContactCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'contactable_type',
        'contactable_id',
        'label',
        'phone',
        'email',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'zip',
        'is_active',
        'is_primary',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'is_primary' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function contactable(): MorphTo
    {
        return $this->morphTo();
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */
    public function getContactSummaryAttribute(): string
    {
        $lines = [];

        if ($this->phone) {
            $lines[] = "📞 {$this->phone}";
        }

        if ($this->email) {
            $lines[] = "✉️ {$this->email}";
        }

        if ($this->formatted_address) {
            $lines[] = $this->formatted_address;
        }

        return $lines
            ? implode('<br>', $lines)
            : '<span class="text-gray-400">—</span>';
    }

    public function getDisplayPhoneAttribute(): string
    {
        return $this->phone ?? '—';
    }

    public function getDisplayEmailAttribute(): string
    {
        return $this->email ?? '—';
    }

    public function getDisplayLabelAttribute(): string
    {
        if ($this->label) {
            return $this->label;
        }

        $pieces = array_filter([$this->phone, $this->email]);
        return $pieces ? implode(' - ', $pieces) : 'Unnamed Contact';
    }

    public function getFormattedAddressAttribute(): string
    {
        $parts = [];

        if ($this->address_line_1) {
            $parts[] = $this->address_line_1;
        }

        if ($this->address_line_2) {
            $parts[] = $this->address_line_2;
        }

        $cityStateZip = trim(
            implode(' ', array_filter([
                $this->city ? "{$this->city}," : null,
                $this->state,
                $this->zip,
            ]))
        );

        if ($cityStateZip) {
            $parts[] = $cityStateZip;
        }

        return implode(', ', $parts);
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
    public function markAsPrimary(): void
    {
        static::where('contactable_type', $this->contactable_type)
            ->where('contactable_id', $this->contactable_id)
            ->update(['is_primary' => false]);

        $this->update(['is_primary' => true]);
    }
}
