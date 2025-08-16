<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RDSanction extends Model
{
    use HasFactory;

    protected $table = "rd_sanction";
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

    public function progress()
    {
       return $this->hasOne(Progress::class,'work','work'); 
    }
}
