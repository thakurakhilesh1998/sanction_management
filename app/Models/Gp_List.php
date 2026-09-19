<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pghar_Image;
use App\Models\PriAsset;
use App\Models\PriAssetSubmission;

class Gp_List extends Model
{
    use HasFactory;
    protected $table="gp_list";
    protected $fillable=['district_name','block_name','gp_name'];

    public function pghar_image()
    {
        return $this->hasMany(Pghar_Image::class,'gp_id');
    }

        public function priAssets()
    {
        return $this->hasMany(
            PriAsset::class,
            'gp_id',
            'id'
        );
    }
    public function priAssetSubmission()
{
    return $this->hasOne(PriAssetSubmission::class, 'gp_id');
}
}
