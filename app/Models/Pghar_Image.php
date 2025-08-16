<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Gp_List;

class Pghar_Image extends Model
{
    use HasFactory;
    protected $table="pghar_image";
    protected $fillable=['gp_id','image_path','rooms','lat','long','remarks'];

    public function gp_list()
    {
        return $this->belongsTo(Gp_List::class,'gp_id','id');
    }
}
