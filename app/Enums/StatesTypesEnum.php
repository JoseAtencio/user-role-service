<?php

namespace App\Enums;
//(dni, cif, nie, nif, passport, other)
enum StatesTypesEnum: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    
}