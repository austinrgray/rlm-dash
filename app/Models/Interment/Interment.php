<?php

namespace App\Models\Interment;

use App\Enums\Interment\DispositionType;
use App\Enums\Interment\VaultType;
use App\Enums\Interment\IntermentStatus;
use App\Models\Cemetery\ExternalCemetery;
use App\Models\Cemetery\Plot;
use App\Models\Cemetery\Mausoleum\Crypt;
use App\Models\Cemetery\Columbarium\Niche;
use App\Models\Customer\Person;
use App\Models\Finance\Invoice;
use App\Models\Finance\InvoiceLineItem;
use App\Models\Operation\Order;
use App\Models\Interment\FuneralHome;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Interment extends Model
{
    use HasFactory;

    protected $fillable = [
        'person_id',
        'intermentable_id',
        'intermentable_type',
        'date_of_interment',
        'disposition_type',
        'vault_type',
        'funeral_home_id',
        'status',
        'invoice_id',
        'invoice_line_item_id',
    ];

    protected $casts = [
        'date_of_interment' => 'date',
        'disposition_type'  => DispositionType::class,
        'vault_type'        => VaultType::class,
        'status'            => IntermentStatus::class,
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function intermentable(): MorphTo
    {
        // Plot | Crypt | Niche | ExternalCemetery
        return $this->morphTo();
    }

    public function funeralHome(): BelongsTo
    {
        return $this->belongsTo(FuneralHome::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function invoiceLineItem(): BelongsTo
    {
        return $this->belongsTo(InvoiceLineItem::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */
    public function getDateOfDeathAttribute(): ?\Illuminate\Support\Carbon
    {
        return $this->person?->date_of_death;
    }

    /*
    |--------------------------------------------------------------------------
    | Validation / Business Logic
    |--------------------------------------------------------------------------
    */
    public function validateVault(): void
    {
        if ($this->disposition_type && $this->vault_type) {
            if (! $this->disposition_type->isValidVault($this->vault_type)) {
                throw new \DomainException(
                    "Vault type {$this->vault_type->value} is not valid for disposition {$this->disposition_type->value}."
                );
            }
        }
    }

    protected static function booted(): void
    {
        static::saving(function (Interment $interment) {
            // vault check (from earlier)
            $interment->validateVault();

            // disposition vs intermentable consistency check
            $validDispositions = match ($interment->intermentable_type) {
                Plot::class => [
                    DispositionType::TraditionalBurial,
                    DispositionType::CremationBurial,
                    DispositionType::CremationScatter,
                ],
                Crypt::class => [
                    DispositionType::CryptEntombment,
                ],
                Niche::class => [
                    DispositionType::NicheInurnment,
                ],
                ExternalCemetery::class => DispositionType::cases(),
                default => [DispositionType::Unknown],
            };

            if (! in_array($interment->disposition_type, $validDispositions, true)) {
                throw new \DomainException(
                    "Disposition {$interment->disposition_type->value} is not valid for {$interment->intermentable_type}"
                );
            }

            // death vs interment check
            if ($interment->date_of_interment && $interment->person?->date_of_death) {
                if ($interment->date_of_interment->lt($interment->person->date_of_death)) {
                    throw new \DomainException("Interment cannot occur before date of death.");
                }
            }
        });
    }
}
