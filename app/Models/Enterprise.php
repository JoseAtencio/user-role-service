<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Enterprise extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'document_number',
        'document_type',
        'state',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function activities()
    {
        return $this->belongsToMany(Activity::class);
    }

}
