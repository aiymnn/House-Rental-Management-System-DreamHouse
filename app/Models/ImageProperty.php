<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageProperty extends Model
{
    use HasFactory;

    protected $table = 'image_properties';

    protected $fillable = [
        'property_id',
        'image'
    ];
}
