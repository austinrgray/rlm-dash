<?php

namespace App\Enums\Interment;

enum VaultType: string
{
    case Concrete = 'concrete';
    case Steel    = 'steel';
    case Bronze   = 'bronze';
    case Eco      = 'Eco';
    case Child    = 'child';
    case Urn      = 'urn';
    case None     = 'none';
    case Unknown  = 'unknown';

    /**
     * A short human-readable label.
     */
    public function label(): string
    {
        return match ($this) {
            self::Concrete => 'Concrete Vault',
            self::Steel    => 'Steel Vault',
            self::Bronze   => 'Bronze Vault',
            self::Eco      => 'Eco Vault',
            self::Child    => 'Child Vault',
            self::Urn      => 'Urn Vault',
            self::None     => 'No Vault',
            self::Unknown  => 'Unknown Vault',
        };
    }

    /**
     * Return vaults grouped by general category.
     */
    public static function categories(): array
    {
        return [
            'Burial' => [
                self::Concrete,
                self::Steel,
                self::Bronze,
                self::Eco,
                self::Child,
                self::None,
            ],
            'Cremation' => [
                self::Urn,
                self::None,
            ],
            'General' => [
                self::Unknown,
            ],
        ];
    }
}
