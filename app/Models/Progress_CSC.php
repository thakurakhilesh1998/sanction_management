<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progress_CSC extends Model
{
    use HasFactory;
    protected $table = 'progress_csc';
     protected $fillable = [
        'p_update',
        'completion_percentage',
        'remarks',
        'work',
        'district',
        'block',
        'gp'
    ];

    public function sanction()
    {
         return $this->belongsTo(CSCSanction::class, 'work', 'work');
    }
}
