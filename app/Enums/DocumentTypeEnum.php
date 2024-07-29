<?php

namespace App\Enums;
//(dni, cif, nie, nif, passport, other)
enum DocumentTypeEnum: string
{
    case PASSPORT = 'passport';
    case DNI = 'dni';
    case CIF = 'cif';
    case NIE = 'nie';
    case NIF = 'nif';
    case OTHER = 'other';
    case DRIVER_LICENSE = 'driver_license';
}