<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Progress;

class Image extends Model
{
    use HasFactory;
    protected $table="image";
    protected $fillable=['image_path','progress_id','work_started_image','work_partial_image','work_completed_image'];

    public function progress()
    {
       return $this->belongsTo(Progress::class,'progress_id','id');     
    }

}
