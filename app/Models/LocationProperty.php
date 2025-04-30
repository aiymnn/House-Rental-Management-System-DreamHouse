<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationProperty extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'latitude',
        'longitude',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}

