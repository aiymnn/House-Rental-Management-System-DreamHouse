<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $table = 'appointments';

    protected $fillable = [
        'property_id',
        'tenant_id',
        'agent_id',
        'date',
        'time',
    ];

    public function agent()
    {
        return $this->belongsTo(Staff::class, 'agent_id');
    }

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }
}
