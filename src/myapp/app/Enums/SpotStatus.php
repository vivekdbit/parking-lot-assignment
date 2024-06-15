<?php
namespace App\Enums;

use Illuminate\Validation\Rules\Enum;

enum SpotStatus: string
{
    case PARK = 'PARK';
    case UNPARK = 'UNPARK';
    case RESERVED = 'RESERVED';
}