<?php

namespace App\Models;

use App\Models\Staff;
use App\Models\Tenant;
use App\Models\Property;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contract extends Model
{
    use HasFactory;

    protected $table = 'contracts';

    protected $fillable = [
        'property_id',
        'agent_id',
        'tenant_id',
        'period',
        'deposit',
        'total',
        'balance',
        'start_date',
        'end_date',
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

    public function payments()
    {
        return $this->hasMany(Payment::class, 'contract_id');
    }
}
