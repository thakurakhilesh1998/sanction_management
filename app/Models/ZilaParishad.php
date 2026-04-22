<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZilaParishad extends Model
{
    use HasFactory;
    protected $fillable = [
        'ward_no',
        'district',
        'ward_name',
        'designation',
        'name',
        'address',
        'pincode',
        'mobile',
        'reservation_status'
    ];
}
