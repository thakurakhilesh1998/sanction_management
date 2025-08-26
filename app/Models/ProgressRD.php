<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgressRD extends Model
{
    use HasFactory;
    protected $table = "progress_rd";

    protected $fillable = [
        'p_update',
        'completion_percentage',
        'remarks',
        'work',
        'district',
        'block',
        'work'
    ];

    public function rdSanction()
    {
        return $this->belongsTo(RDSanction::class, 'work', 'work');
    }

    public function images()
    {
        return $this->hasOne(ProgressRDImage::class, 'progress_id', 'id');
    }   
}
