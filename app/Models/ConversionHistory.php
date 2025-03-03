<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConversionHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_currency',
        'to_currency',
        'amount',
        'converted_amount',
    ];
}