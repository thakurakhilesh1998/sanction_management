<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CSCSanction extends Model
{
    use HasFactory;
    protected $table = "csc_sanction";
    protected $fillable = [
        'financial_year',
        'district',
        'block',
        'san_amount',
        'sanction_date',
        'sanction_head',
        'sanction_purpose',
        'agency',
        'work',
        'san_pdf',
        'uc'
    ];
}
