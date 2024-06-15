<?php
namespace App\Enums;

use Illuminate\Validation\Rules\Enum;

enum SpotType: string
{
    case CAR = 'CAR';
    case MOTORCYCLE = 'MOTORCYCLE';
    case VAN = 'VAN';
}