<?php

namespace App\Enums\Interment;

use App\Enums\Interment\VaultType;

enum DispositionType: string
{
    case TraditionalBurial  = 'traditional_burial';
    case CremationBurial    = 'cremation_burial';
    case CremationScatter   = 'cremation_scatter';
    case CryptEntombment    = 'crypt_entombment';
    case NicheInurnment     = 'niche_inurnment';
    case Unknown            = 'unknown';

    public function allowedVaultTypes(): array
    {
        return match ($this) {
            self::TraditionalBurial => [
                VaultType::Concrete,
                VaultType::Steel,
                VaultType::Bronze,
                VaultType::Eco,
                VaultType::Child,
                VaultType::None,
                VaultType::Unknown,
            ],
            self::CremationBurial => [
                VaultType::Urn,
                VaultType::None,
                VaultType::Unknown,
            ],
            self::CremationScatter => [
                VaultType::None,
            ],
            self::CryptEntombment => [
                VaultType::Concrete,
                VaultType::Steel,
                VaultType::Unknown,
            ],
            self::NicheInurnment => [
                VaultType::Urn,
            ],
            self::Unknown => [
                VaultType::Unknown,
            ],
        };
    }

    /**
     * Validate that a vault type is allowed for this disposition.
     */
    public function isValidVault(VaultType $vault): bool
    {
        return in_array($vault, $this->allowedVaultTypes(), true);
    }
}
