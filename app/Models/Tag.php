<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'tag_no',
        'staff_name',
        'email',
        'phone',
        'appointment',
        'department',
        'address',
        'vehicle_type',
        'vehicle_model',
        'vehicle_color',
        'vehicle_plate_no',
        'vehicle_chasis_no',
        'authorized_staff_name',
        'authorized_staff_appointment',
        'slug'
    ];
}
