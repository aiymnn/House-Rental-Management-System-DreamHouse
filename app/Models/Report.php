<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $table = 'reports';

    protected $fillable = [
        'agent_id',
        'tenant_id',
        'title',
        'description',
        'image',
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
