<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GramPanchayat extends Model
{
    use HasFactory;
     protected $table = 'gram_panchayats';

    protected $fillable = [
        'district',
        'ps_name',
        'gp_name',
        'designation',
        'name',
        'address',
        'pincode',
        'mobile',
        'reservation_status',
    ];
}
