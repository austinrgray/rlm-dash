<?php

namespace App\Enums\Finance;

use App\Enums\Finance\LineItemCategory;
use App\Enums\Interment\DispositionType;

enum LineItemCode: string
{
    /*
    |--------------------------------------------------------------------------
    | Rights
    |--------------------------------------------------------------------------
    */
    case BurialRightPlot     = 'burial_right_plot';
    case BurialRightCrypt    = 'burial_right_crypt';
    case BurialRightNiche    = 'burial_right_niche';

        /*
    |--------------------------------------------------------------------------
    | Services: Interments
    |--------------------------------------------------------------------------
    */
    case IntermentTraditionalBurial = 'interment_traditional_burial';
    case IntermentCremationBurial   = 'interment_cremation_burial';
    case IntermentCremationScatter  = 'interment_cremation_scatter';
    case IntermentCryptEntombment   = 'interment_crypt_entombment';
    case IntermentNicheInurnment    = 'interment_niche_inurnment';

        /*
    |--------------------------------------------------------------------------
    | Services: Disinterments
    |--------------------------------------------------------------------------
    */
    case DisintermentTraditionalBurial = 'disinterment_traditional_burial';
    case DisintermentCremationBurial   = 'disinterment_cremation_burial';
    case DisintermentCryptEntombment   = 'disinterment_crypt_entombment';
    case DisintermentNicheInurnment    = 'disinterment_niche_inurnment';

        /*
    |--------------------------------------------------------------------------
    | Services: Grave Openings
    |--------------------------------------------------------------------------
    */
    case GraveOpeningTraditionalBurial = 'grave_opening_traditional_burial';
    case GraveOpeningCremationBurial   = 'grave_opening_cremation_burial';
    case GraveOpeningCryptEntombment   = 'grave_opening_crypt_entombment';
    case GraveOpeningNicheInurnment    = 'grave_opening_niche_inurnment';

        /*
    |--------------------------------------------------------------------------
    | Services: Monument & Marker Work
    |--------------------------------------------------------------------------
    */
    case MonumentFoundation   = 'monument_foundation';
    case MonumentInstallation = 'monument_installation';
    case MarkerFoundation   = 'marker_foundation';
    case MarkerInstallation   = 'marker_installation';
    case Sandblasting         = 'sandblasting';
    case Engraving            = 'engraving';
    case Polishing            = 'polishing';
    case Cleaning             = 'cleaning';
    case Repair               = 'repair';
    case Resetting            = 'resetting'; // lifting & re-setting stone
    case Leveling             = 'leveling';  // adjust stone alignment
    case Removal              = 'removal';   // removing stone for replacement/storage

        /*
    |--------------------------------------------------------------------------
    | Products
    |--------------------------------------------------------------------------
    */
    case Marker      = 'marker';
    case Monument    = 'monument';
    case Urn         = 'urn';

        /*
    |--------------------------------------------------------------------------
    | Subscriptions
    |--------------------------------------------------------------------------
    */
    case PerpetualCare = 'perpetual_care';
    case FloralProgram = 'floral_program';

        /*
    |--------------------------------------------------------------------------
    | Adjustments
    |--------------------------------------------------------------------------
    */
    case Discount    = 'discount';
    case Misc        = 'misc';

    /*
    |--------------------------------------------------------------------------
    | Category Mapping
    |--------------------------------------------------------------------------
    */
    public function category(): LineItemCategory
    {
        return match ($this) {
            // Rights
            self::BurialRightPlot,
            self::BurialRightCrypt,
            self::BurialRightNiche
            => LineItemCategory::Right,

            // Interments, Disinterments, Grave Openings
            self::IntermentTraditionalBurial,
            self::IntermentCremationBurial,
            self::IntermentCremationScatter,
            self::IntermentCryptEntombment,
            self::IntermentNicheInurnment,
            self::DisintermentTraditionalBurial,
            self::DisintermentCremationBurial,
            self::DisintermentCryptEntombment,
            self::DisintermentNicheInurnment,
            self::GraveOpeningTraditionalBurial,
            self::GraveOpeningCremationBurial,
            self::GraveOpeningCryptEntombment,
            self::GraveOpeningNicheInurnment,

            // Monument/Marker services
            self::MonumentFoundation,
            self::MonumentInstallation,
            self::MarkerFoundation,
            self::MarkerInstallation,
            self::Sandblasting,
            self::Engraving,
            self::Polishing,
            self::Cleaning,
            self::Repair,
            self::Resetting,
            self::Leveling,
            self::Removal
            => LineItemCategory::Service,

            // Products
            self::Marker,
            self::Monument,
            self::Urn
            => LineItemCategory::Product,

            // Subscriptions
            self::PerpetualCare,
            self::FloralProgram
            => LineItemCategory::Subscription,

            // Adjustments
            self::Discount,
            self::Misc
            => LineItemCategory::Adjustment,
        };
    }

    /*
    |--------------------------------------------------------------------------
    | Mapping to DispositionType (Interments only)
    |--------------------------------------------------------------------------
    */
    public static function fromDisposition(DispositionType $disposition): self
    {
        return match ($disposition) {
            DispositionType::TraditionalBurial => self::IntermentTraditionalBurial,
            DispositionType::CremationBurial   => self::IntermentCremationBurial,
            DispositionType::CremationScatter  => self::IntermentCremationScatter,
            DispositionType::CryptEntombment   => self::IntermentCryptEntombment,
            DispositionType::NicheInurnment    => self::IntermentNicheInurnment,
            default                            => self::Misc,
        };
    }

    public function toDisposition(): ?DispositionType
    {
        return match ($this) {
            self::IntermentTraditionalBurial   => DispositionType::TraditionalBurial,
            self::IntermentCremationBurial     => DispositionType::CremationBurial,
            self::IntermentCremationScatter    => DispositionType::CremationScatter,
            self::IntermentCryptEntombment     => DispositionType::CryptEntombment,
            self::IntermentNicheInurnment      => DispositionType::NicheInurnment,
            default                            => null,
        };
    }

    /*
    |--------------------------------------------------------------------------
    | Validation Helpers
    |--------------------------------------------------------------------------
    */
    public function isValidCategory(LineItemCategory $category): bool
    {
        return $this->category() === $category;
    }
}
