<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pghar_Image;

class Gp_List extends Model
{
    use HasFactory;
    protected $table="gp_list";
    protected $fillable=['district_name','block_name','	gp_name'];

    public function pghar_image()
    {
        return $this->hasMany(Pghar_Image::class,'gp_id');
    }
}
