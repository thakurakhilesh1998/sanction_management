<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Progress;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sanction extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $table="sanction";
    protected $fillable=['financial_year','district','block','gp','newGP','san_amount','sanction_date','sanction_head','sanction_purpose','ac','status','revert'];

    public function progress()
    {
        return $this->hasOne(Progress::class,'gp','gp');
    }
}