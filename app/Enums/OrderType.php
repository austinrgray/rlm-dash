<?php

namespace App\Enums;

enum OrderType: string
{
    case CemeteryTraditionalInterment = 'cemetery_traditional_interment';
    case CemeteryTraditionalDisinterment = 'cemetery_traditional_disinterment';
    case CemeteryCremationInterment = 'cemetery_cremation_interment';
    case CemeteryCremationDisinterment = 'cemetery_cremation_disinterment';
    case CemeteryMausoleumInterment = 'cemetery_mausoleum_interment';
    case CemeteryMausoleumDisinterment = 'cemetery_mausoleum_disinterment';
    case CemeteryColumbariumInterment = 'cemetery_columbarium_interment';
    case CemeteryColumbariumDisinterment = 'cemetery_columbarium_disinterment';
    case CemeteryScattering = 'cemetery_scattering';
    case CemeteryMemorialService = 'cemetery_memorial_service';
    case CemeteryOther = 'cemetery_other';

    case MonumentPurchase = 'monument_purchase';
    case MonumentInstallation = 'monument_installation';
    case MonumentEngraving = 'monument_engraving';
    case MonumentRepair = 'monument_repair';
    case MonumentAccessoryPurchase = 'monument_accessory_purchase';
    case MonumentOther = 'monument_other';
}
