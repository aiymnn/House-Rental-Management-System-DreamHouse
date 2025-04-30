<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Property extends Model
{
    use HasFactory;

    protected $table = 'properties';

    protected $fillable = [
        'address',
        'type',
        'deposit',
        'monthly',
        'description',
        'landlord_id',
    ];

    public function landlord()
    {
        return $this->belongsTo(Landlord::class, 'landlord_id');
    }

    public function agent()
    {
        return $this->belongsTo(Staff::class, 'agent_id');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function images()
    {
        return $this->hasMany(ImageProperty::class);
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    // In Property model
    public function locationProperty()
    {
        return $this->hasOne(LocationProperty::class, 'property_id');
    }



}

