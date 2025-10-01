<?php

namespace App\Models\Cemetery;

use App\Models\Shared\ContactCard;
use App\Models\Finance\Invoice;
// use App\Models\Finance\Order;
use App\Models\Interment\Interment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class InternalCemetery extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function contactCards(): MorphMany
    {
        return $this->morphMany(ContactCard::class, 'contactable');
    }

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class);
    }

    public function lots(): HasManyThrough
    {
        return $this->hasManyThrough(Lot::class, Section::class);
    }

    public function plots(): HasMany
    {
        return $this->hasMany(Plot::class);
    }

    public function mausoleums(): HasMany
    {
        return $this->hasMany(Mausoleum\Mausoleum::class);
    }

    public function crypts(): HasMany
    {
        return $this->hasMany(Mausoleum\Crypt::class);
    }

    public function columbaria(): HasMany
    {
        return $this->hasMany(Columbarium\Columbarium::class);
    }

    public function niches(): HasMany
    {
        return $this->hasMany(Columbarium\Niche::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    // public function payments(): HasMany
    // {
    //     return $this->hasMany(Payment::class);
    // }

    // public function orders(): HasMany
    // {
    //     return $this->hasMany(Order::class);
    // }
}
