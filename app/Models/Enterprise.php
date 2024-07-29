<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

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

    public function scopeFilterByAttributes(Builder $query, $filters)
    {
        if (isset($filters['document_type'])) {
            $query->where('document_type', 'like', '%' . $filters['document_type'] . '%');
        }
        if (isset($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }
        if (isset($filters['state'])) {
            $query->where('state', 'like', '%' . $filters['state'] . '%');
        }
        return $query;
    }

    public function scopeWithoutActivities(Builder $query)
    {
        return $query->doesntHave('activities');
    }

}
