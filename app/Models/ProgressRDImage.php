<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgressRDImage extends Model
{
    use HasFactory;
    protected $table = 'progress_rd_image';

    protected $fillable = [
        'progress_id',
        'work_started_image',
        'work_partial_image',
        'work_completed_image',
    ];
      public function progress()
    {
        return $this->belongsTo(ProgressRD::class, 'progress_id', 'id');
    }
}
