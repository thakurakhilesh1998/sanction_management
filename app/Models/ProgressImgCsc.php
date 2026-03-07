<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgressImgCsc extends Model
{
    use HasFactory;
    protected $table = 'progress_img_csc';
       protected $fillable = [
        'progress_id',
        'work_started_image',
        'work_partial_image',
        'work_completed_image'
    ];
     public function progress()
     {
        return $this->belongsTo(Progress::class, 'progress_id');
     }

    public function images()
    {
        return $this->hasOne(ProgressImgCsc::class, 'progress_id');
    }
}
