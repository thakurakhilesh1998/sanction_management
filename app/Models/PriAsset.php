<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class PriAsset extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pri_assets';

    protected $fillable = [
        'asset_uuid',
        'gp_id',
        'asset_category',
        'asset_sub_category',
        'building_name',
        'ownership',
        'panchayat_office_status',
        'latitude',
        'longitude',
        'location_accuracy',
        'location_captured_at',
        'asset_image',
        'is_active',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'location_accuracy' => 'decimal:2',
        'location_captured_at' => 'datetime',
        'is_active' => 'boolean',
    ];


    /*
    |--------------------------------------------------------------------------
    | Automatically generate Asset UUID
    |--------------------------------------------------------------------------
    */

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($asset) {

            if (empty($asset->asset_uuid)) {
                $asset->asset_uuid = (string) Str::uuid();
            }

        });
    }


    /*
    |--------------------------------------------------------------------------
    | Relationship with GP List
    |--------------------------------------------------------------------------
    */

    public function gramPanchayat()
    {
        return $this->belongsTo(
            GpList::class,
            'gp_id',
            'id'
        );
    }
}