<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PanchayatSamiti extends Model
{
    use HasFactory;
    protected $table = 'panchayat_samitis';

    protected $fillable = [
        'district',
        'ps_name',
        'ward_no',
        'ward_name',
        'designation',
        'name',
        'address',
        'pincode',
        'mobile',
        'reservation_status',
    ];
}
