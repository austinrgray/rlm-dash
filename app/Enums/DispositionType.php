<?php

namespace App\Enums;

enum DispositionType: string
{
    case TraditionalBurial = 'traditional_burial';
    case CremationBurial = 'cremation_burial';
    case MausoleumEntombment = 'mausoleum_entombment';
    case ColumbariumEntombment = 'columbarium_entombment';
    case Other = 'other';
    case Unknown = 'unknown';
}
