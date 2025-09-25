<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ContactCard extends Model
{
    /** @use HasFactory<\Database\Factories\ContactCardFactory> */
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
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function contactable(): MorphTo
    {
        return $this->morphTo();
    }
}
